<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\BaseController;
use App\Http\Interfaces\BreederControllerInterface;
use App\Http\Requests\CreateBreederRequest;
use App\Http\Requests\DeleteBreederRequest;
use App\Http\Requests\GetBreederRequest;
use App\Http\Requests\UpdateBreederRequest;
use App\Http\Resources\BaseCollection;
use App\Models\User;
use App\Repository\API\BreederRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BreederController extends BaseController implements BreederControllerInterface
{
    public function __construct(BreederRepo $breederRepository)
    {
        $this->service = $breederRepository;
    }

    public function index(GetBreederRequest $request): BaseCollection
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show(GetBreederRequest $request, int $id): JsonResponse
    {
        $with = $request->toArray()['with'] ?? null;
        $count = $request->toArray()['count'] ?? null;

        if ($with)
            $this->service->appendWith(explode(',',$with));
        if ($count)
            $this->service->appendCount(explode(',',$count));

        return $this->sendResponse($this->service->find($id));
    }

    /**
     * @throws \Exception
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
                'password' => $data['password'] ? bcrypt($data['password']) : null,
            ]);

            $breederUser->assignRole(Role::BREEDER->value);

            $breederUser->accounts()->create([
                'user_id' => $breederUser->id,
                'app_id' => 2,
                'approved_at' => now(),
            ]);

            // set breeder as the user owner
            $data = array_merge($data, ['user_id' => $breederUser->id]);
            $result = $this->service->create($data);

            DB::commit();

            if (!$breederUser->hasVerifiedEmail()) {
                $breederUser->sendEmailVerificationViaFocalPersonNotification();
            }

            return $result;
        } catch (\Exception $e)
        {
            DB::rollBack();
        }
    }



    public function update(UpdateBreederRequest $request, int $id): JsonResponse
    {
        $data = $this->insertUserId($request->validated());
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');
        return $this->service->update($id, $data);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteBreederRequest $request): JsonResponse
    {
        return $this->service->multiDestroy($request->validated());
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
            ->selectRaw('name as label, count(*) as total')
            ->groupBy('name')
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
        if (empty($data)) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json(['data' => $data[0]->commodities]);
    }
}
