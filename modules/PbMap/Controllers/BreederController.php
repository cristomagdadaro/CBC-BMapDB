<?php

namespace Modules\PbMap\Controllers;

use App\Enums\DefaultPassword;
use App\Enums\Role;
use App\Http\Controllers\BaseController;
use App\Http\Resources\BaseCollection;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\PbMap\Interfaces\BreederControllerInterface;
use Modules\PbMap\Repositories\BreederRepo;
use Modules\PbMap\Requests\CreateBreederRequest;
use Modules\PbMap\Requests\DeleteBreederRequest;
use Modules\PbMap\Requests\GetBreederRequest;
use Modules\PbMap\Requests\UpdateBreederRequest;

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

    // implement this to other controllers, api for selection option field
    public function selection(GetBreederRequest $request): BaseCollection
    {
        $this->authorize('viewAny', $this->service->model);
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
        $this->authorize('create', $this->service->model);
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
        return parent::_update($request, $id);
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
        if (auth()->check())
            return $this->summaryPrivate($request);
        return $this->summaryPublic($request);
    }

    private function summaryPrivate(GetBreederRequest $request): JsonResponse
    {
        $model = $this->service->model;

        $geo_location_filter = $request->validated('geo_location_filter') ?? 'region';
        $geo_location_value = $request->validated('geo_location_value') ?? '';
        $is_exact = $request->validated('is_exact');
        $breeder = $request->all()['breeder'] ?? null;

        $builder = $model->newModelQuery();

        $this->service->applyGeoFilters($builder, $request->collect());
        $this->service->applySearchFilters($builder, $request->collect());

        $builderA = (clone $builder);
        $this->service->applyAppends($builderA, $request->collect());
        $breeders = $builderA->select($model->getSearchable())->get();

        $group_by = $this->service->determineLocFilterLevel($geo_location_filter ?? 'region');
        $temp = $request->collect()->put('select_raw', "$group_by as label, count(*) as total");
        $temp =  $temp->put('group_by', $group_by);
        $temp =  $temp->put('sort', 'total');
        $temp =  $temp->put('order', 'desc');

        $builderB = (clone $builder)->selectRaw("$group_by as label, count(*) as total");;
        $this->service->applySorting($builderB, $temp);
        $chart_data = $builderB->groupBy($group_by)->get();

        $builderC = (clone $builder)->selectRaw('CONCAT(breeders.fname, breeders.mname, breeders.lname, breeders.suffix) as label, count(*) as total');
        $this->service->applySorting($builderC, $temp);
        $breeders_chart = $builderC->groupBy('label')->get();

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

    private function summaryPublic(GetBreederRequest $request): JsonResponse
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
