<?php

namespace Modules\PbMap\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Resources\BaseCollection;
use Exception;
use Illuminate\Http\JsonResponse;
use Modules\PbMap\Actions\CreateBreederAction;
use Modules\PbMap\Actions\GenerateBreederSummaryAction;
use Modules\PbMap\Interfaces\BreederControllerInterface;
use Modules\PbMap\Repositories\BreederRepo;
use Modules\PbMap\Requests\CreateBreederRequest;
use Modules\PbMap\Requests\DeleteBreederRequest;
use Modules\PbMap\Requests\GetBreederRequest;
use Modules\PbMap\Requests\UpdateBreederRequest;

class BreederController extends BaseController implements BreederControllerInterface
{
    private CreateBreederAction $createBreederAction;
    private GenerateBreederSummaryAction $generateBreederSummaryAction;

    public function __construct(
        BreederRepo $breederRepository,
        CreateBreederAction $createBreederAction,
        GenerateBreederSummaryAction $generateBreederSummaryAction
    ) {
        $this->service = $breederRepository;
        $this->createBreederAction = $createBreederAction;
        $this->generateBreederSummaryAction = $generateBreederSummaryAction;
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

    public function noPage(int $id, GetBreederRequest $request): JsonResponse
    {
        $breeder = $this->service->model->find($id);

        if (!$breeder) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $commodities = $breeder->commodities;

        return response()->json(['data' => $commodities]);
    }

    /**
     * @throws Exception
     */
    public function store(CreateBreederRequest $request): JsonResponse
    {
        $this->authorize('create', $this->service->model);
        $breederData = $this->createBreederAction->execute($request);
        return $this->service->create($breederData);
    }

    public function update(UpdateBreederRequest $request, int $id): JsonResponse
    {
        return parent::_update($request, $id);
    }

    public function destroy(DeleteBreederRequest $request, int $id): JsonResponse
    {
        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteBreederRequest $request): JsonResponse
    {
        return parent::_multiDestroy($request);
    }

    public function summary(GetBreederRequest $request): JsonResponse
    {
        return $this->generateBreederSummaryAction->execute($request);
    }
}
