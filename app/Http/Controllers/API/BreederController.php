<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBreederRequest;
use App\Http\Requests\DeleteBreederRequest;
use App\Http\Requests\GetBreederRequest;
use App\Http\Requests\UpdateBreederRequest;
use App\Http\Resources\BreederCollection;
use App\Http\Resources\BreederResource;
use App\Repository\API\BreederRepository;
use Illuminate\Support\Facades\Request;

class BreederController extends Controller
{

    public function __construct(BreederRepository $breederRepository)
    {
        $this->repository = $breederRepository;
    }

    public function index(GetBreederRequest $request)
    {
        $data = $this->repository->search($request->collect());
        return new BreederCollection($data);
    }

    public function store(CreateBreederRequest $request)
    {
        $data = $this->repository->create($request->validated());
        return new BreederResource($data);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function update(UpdateBreederRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

    public function multiDestroy(DeleteBreederRequest $request)
    {
        return $this->repository->multiDestroy($request->validated());
    }
}
