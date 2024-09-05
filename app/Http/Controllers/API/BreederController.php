<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetBreederRequest;
use App\Http\Requests\CreateBreederRequest;
use App\Http\Requests\UpdateBreederRequest;
use App\Http\Requests\DeleteBreederRequest;
use App\Models\Breeder;
use App\Repository\API\BreederRepo;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BaseCollection;

class BreederController extends BaseController
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

    public function summary(GetBreederRequest $request)
    {
        $model = $this->service->model;
        $group_by = $request->validated('group_by') ?? 'region';
        $search = $request->validated('search') ?? '';
        $is_exact = $request->validated('is_exact');

        if ($search)
            return response()->json([
                'group_search_labels' => $this->getGroupByLabels($group_by),
                'chart_data' => $model->selectRaw($group_by.' as label, count(*) as total')
                    ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                        if ($is_exact == 'true') {
                            return $query->where($group_by, $search);
                        } else {
                            return $query->where($group_by, 'like', '%'.$search.'%');
                        }
                    })
                    ->groupBy($group_by)
                    ->orderBy('total', 'desc')
                    ->get(),
                'breeders' => $model->when($search, function ($query) use ($search, $is_exact, $group_by) {
                    if ($is_exact == 'true') {
                        return $query->where($group_by, $search);
                    } else {
                        return $query->where($group_by, 'like', '%'.$search.'%');
                    }
                })
                    ->where($group_by, 'like', '%'.$search.'%')
                    ->get(),
                'breeders_chart' => $model->selectRaw('name as label, count(*) as total')
                    ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                        if ($is_exact == 'true') {
                            return $query->where($group_by, $search);
                        } else {
                            return $query->where($group_by, 'like', '%'.$search.'%');
                        }
                    })->groupBy('name')
                    ->orderBy('total', 'desc')
                    ->get()
            ]);

        return response()->json([
            'group_search_labels' => $this->getGroupByLabels($group_by),
            'chart_data' => $model->selectRaw($group_by.' as label, count(*) as total')
                ->groupBy($group_by)
                ->orderBy('total', 'desc')
                ->get(),
            'breeders' => $model->when($search, function ($query) use ($search, $is_exact, $group_by) {
                if ($is_exact == 'true') {
                    return $query->where($group_by, $search);
                } else {
                    return $query->where($group_by, 'like', '%'.$search.'%');
                }
            })->get(),
            'breeders_chart' => $model->selectRaw('name as label, count(*) as total')
                ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                    if ($is_exact == 'true') {
                        return $query->where($group_by, $search);
                    } else {
                        return $query->where($group_by, 'like', '%'.$search.'%');
                    }
                })
                ->groupBy('name')
                ->orderBy('total', 'desc')
                ->get()
        ]);
    }

    private function getGroupByLabels($group_by)
    {
        return Breeder::select($group_by)->orderBy($group_by)->distinct()->get()->pluck($group_by);
    }

    public function store(CreateBreederRequest $request): JsonResponse
    {
        return $this->service->create($request->validated());
    }

    public function show(int $id): JsonResponse
    {
        return $this->service->find($id);
    }

    public function noPageSearch(int $id, GetBreederRequest $request)
    {
        $this->service->appendWith(['commodities']);
        $data = $this->service->search(new Collection($request->validated()), false);
        if (count($data) === 0) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json(['data' => $data[0]->commodities]);
    }

    public function update(UpdateBreederRequest $request, int $id): JsonResponse
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id): JsonResponse
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteBreederRequest $request): JsonResponse
    {
        return $this->service->multiDestroy($request->validated());
    }
}
