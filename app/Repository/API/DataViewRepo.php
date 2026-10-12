<?php

namespace App\Repository\API;

use App\Models\DataView;
use App\Repository\AbstractRepoService;
use Illuminate\Support\Facades\DB;

class DataViewRepo extends AbstractRepoService
{
    public function __construct(DataView $model)
    {
        parent::__construct($model);
    }

    public function getGroupedDataViews(?string $table = null): array
    {
        return DataView::query()
            ->select('user_account_id', 'model', 'visibility_guard', 'columns')
            ->when($table, fn($query) => $query->where('model', $table))
            ->get()
            ->groupBy('user_account_id')
            ->map(fn($models) =>
                $models->groupBy('model')->map(fn($visibilityGuards) =>
                $visibilityGuards->pluck('columns', 'visibility_guard')->toArray())->toArray()
            )
            ->toArray();
    }

    public function getDataViewsForTable(string $table, $user)
    {
        $query = $this->model->where('model', $table);

        if ($user && !$user->isAdmin()) {
            $query->where('user_account_id', $user->id);
        }

        return $query->get()->keyBy('visibility_guard');
    }

    public function updateByUuid(string $uuid, array $data)
    {
        $dataView = $this->model->where('uuid', $uuid)->firstOrFail();
        $dataView->update($data);
        
        return $dataView;
    }
}
