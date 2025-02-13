<?php

namespace App\Http\Controllers\API;

use App\Enums\DefaultPassword;
use App\Enums\Role;
use App\Http\Controllers\BaseController;
use App\Http\Interfaces\BreederControllerInterface;
use App\Http\Requests\CreateBreederRequest;
use App\Http\Requests\DeleteBreederRequest;
use App\Http\Requests\GetBreederRequest;
use App\Http\Requests\UpdateBreederRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\BaseCollection;
use App\Models\User;
use App\Repository\API\BreederRepo;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BreederController extends BaseController implements BreederControllerInterface
{
    public function __construct(BreederRepo $breederRepository)
    {
        $this->service = $breederRepository;
    }

    public function index(GetBreederRequest $request): BaseCollection
    {
        return parent::_index($request);
    }

    public function show(GetBreederRequest $request, int $id): JsonResponse
    {
        return parent::_show($request, $id);
    }

    /**
     * @throws Exception
     */
    public function store(CreateBreederRequest $request): JsonResponse
    {
        try
        {
            DB::beginTransaction();

            $data = $this->insertUserId($request->validated());

            $breederUser = User::create([
                'fname' => $data['fname'] ?? null,
                'mname' => $data['mname'] ?? null,
                'lname' => $data['lname'] ?? null,
                'suffix' => $data['suffix'] ?? null,
                'mobile_no' => $data['mobile_no'] ?? null,
                'email' => $data['email'] ?? null,
                'affiliation' => $data['affiliation'] ?? null,
                'password' => bcrypt(DefaultPassword::Value->value),
            ]);

            $breederUser->assignRole(Role::BREEDER->value);

            $breederUser->accounts()->create([
                'user_id' => $breederUser->id,
                'app_id' => 2,
                'approved_at' => now(),
            ]);

            if ($request->user()->isAdmin())
                $data = array_merge($data, ['user_id' => $breederUser->id]);

            $result = $this->service->create($data);

            DB::commit();

            if (!$breederUser->hasVerifiedEmail()) {
                $breederUser->sendEmailVerificationViaFocalPersonNotification();
            }

            return $result;
        } catch (Exception $e)
        {
            DB::rollBack();
            throw $e;
        }
    }



    public function update(UpdateBreederRequest $request, int $id): JsonResponse
    {
        $temp = parent::_update($request, $id);
        $user_id = $request->validated()['user_id'] ?? null;
        // Validate using UpdateUserRequest rules
        $validator = Validator::make($request->all(), [
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'mobile_no' =>  ['nullable', 'string', 'max:255', 'unique:users,mobile_no,'.$user_id],
            'affiliation' => ['required', 'exists:institutes,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user_id],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update the corresponding user account
        $user = User::where('id' , $user_id);
        
        if ($request->validated()['email'] !== $user->value('email') &&  $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $validator->validated());
        }

        $user->update($validator->validated());

        return $temp;
    }

    public function destroy(int $id): JsonResponse
    {
        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteBreederRequest $request): JsonResponse
    {
        return parent::_multiDestroy($request);
    }

    public function summary(GetBreederRequest $request): JsonResponse
    {
        $model = $this->service->model;
        $geo_location_filter = $request->validated('geo_location_filter') ?? 'region';
        $geo_location_value = $request->validated('geo_location_value') ?? '';
        $is_exact = $request->validated('is_exact');
        $breeder = $request->all()['breeder'] ?? null;
        $group_by = $this->service->determineLocFilterLevel($geo_location_filter);

        $breeders = $this->service->applyFilters($this->service->checkRole($model), $breeder, $geo_location_value, $geo_location_filter)
            ->select($model->getSearchable())
            ->with(['location', 'commodities','affiliated'])
            ->get();
        $chart_data = $this->service->applyFilters($model, $breeder, $geo_location_value, $geo_location_filter)
            ->selectRaw("$group_by as label, count(*) as total")
            ->groupBy($group_by)
            ->orderBy('total', 'desc')
            ->get();
        $breeders_chart = $this->service->applyFilters($model, $breeder, $geo_location_value, $geo_location_filter)
            ->selectRaw('CONCAT(fname, mname, lname, suffix) as label, count(*) as total')
            ->groupBy('label')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'params' => [
                'breeders' => $breeder,
                'group_by' => $group_by,
                'geo_location_filter' => $geo_location_filter,
                'geo_location_value' => $geo_location_value,
                'is_exact' => $is_exact,
            ],
            'group_search_labels' => $this->service->getGroupByGeoLoc($model, $breeder, $geo_location_filter),
            'group_search_institute' => $this->service->getGroupByInstitute($model, $breeder, $geo_location_filter),
            'raw_data' => $breeders,
            'raw_data_labels' => $this->service->getBreederLabels($model, $geo_location_value, $is_exact, $geo_location_filter),
            'chart_data' => $chart_data,
            'chart_labels' => $breeders_chart,
            'linechart_data' => $this->service->linechartData($model, $geo_location_value, $is_exact, $geo_location_filter),
        ]);
    }

    public function noPage(int $id, GetBreederRequest $request): JsonResponse
    {
        $this->service->appendWith(['commodities']);
        $data = $this->service->search(new Collection($request->validated()), false);
        if (count($data) === 0) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json(['data' => $data[0]->commodities]);
    }
}
