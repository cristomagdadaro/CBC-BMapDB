<?php

namespace Modules\PbMap\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CommodityApprovalScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // If no auth context, only show approved commodities
        if (!auth()->check()) {
            $builder->whereNotNull($model->getTable() . '.approved_at');
            return;
        }

        /** @var User $user */
        $user = auth()->user();

        // Admins see everything
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return;
        }

        $table = $model->getTable();

        // Everyone else: show approved OR (pending visible if allowed)
        $builder->where(function (Builder $q) use ($model, $user, $table) {
            $q->whereNotNull($table . '.approved_at');

            // Breeder can see their own pending commodities
            if (method_exists($user, 'isBreeder') && $user->isBreeder()) {
                $q->orWhere(function (Builder $qq) use ($user, $table) {
                    $qq->whereNull($table . '.approved_at')
                       ->where($table . '.user_id', (int) $user->id);
                });
            }

            // Focal person can see pending commodities within their institute via breeder affiliation
            if (method_exists($user, 'isFocalPerson') && $user->isFocalPerson()) {
                $affId = (int) ($user->affiliation ?? 0);
                if ($affId > 0) {
                    $q->orWhere(function (Builder $qq) use ($affId) {
                        $qq->whereNull('commodities.approved_at')
                           ->whereHas('breeder', function (Builder $bq) use ($affId) {
                               $bq->withoutGlobalScopes()->where('affiliation', $affId);
                           });
                    });
                }
            }
        });
    }
}

