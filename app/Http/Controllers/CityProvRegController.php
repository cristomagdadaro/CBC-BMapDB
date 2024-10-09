<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCityRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\CityRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CityProvRegController extends BaseController
{
    public function __construct(CityRepo $cityProvRegRepository)
    {
        $this->service = $cityProvRegRepository;
    }

    public function cityIndex(GetCityRequest $request)
    {
        //$data = $this->service->search(new Collection($request->validated()));
        $with = $request->query('with');
        $count = $request->query('count');
        $search = explode(',', $request->query('search'));
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);
        $sort = $request->query('sort', 'cityDesc');
        $order = $request->query('order', 'desc');

        if ($with) {
            $with = explode(',', $with);
            $this->service->appendWith($with);
        }

        if ($count) {
            $count = explode(',', $count);
            $this->service->appendCount($count);
        }

        $builder = $this->service->model->newQuery();

        $builder->selectRaw('id, CONCAT(cityDesc, ", ", provDesc, ", ", regDesc) as name, cityDesc, provDesc, regDesc, latitude, longitude');

        foreach ($this->service->removeNullRelationship($this->service->model, $this->service->appendWith) as $table) {
            $builder->with($table);
        }

        foreach ($this->service->removeNullRelationship($this->service->model, $this->service->appendCount) as $table) {
            $builder->withCount($table);
        }



        $builder->where(function ($query) use ($search) {
            foreach ($search as $term) {
                $query->where('id', 'like', "%{$term}%");
                $query->orWhere('cityDesc', 'like', "%{$term}%");
                $query->orWhere('provDesc', 'like', "%{$term}%");
                $query->orWhere('regDesc', 'like', "%{$term}%");
            }
        });

        $data = $builder->orderBy($sort, $order)->paginate($perPage, ['*'], 'page', $page)->withQueryString();

        return new BaseCollection($data);
    }



}

