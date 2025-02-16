<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        $apps = [];
        $accountsPending = [];
        if ($request->user() && $request->user()->accounts)
        {
            foreach ( $request->user()->accounts as $account ) {
                $apps[] = $account->application;
            }
            $accountsPending = $request->user()->accountsPending;
        }

        return array_merge(
            parent::share($request), [
            "permissions" => $this->permissions($request),
            "accounts" => $apps,
            "accountsPending" => $accountsPending,
            "affiliated" => $request->user() ? $request->user()->affiliated : [],
        ]);
    }

    private function permissions(Request $request): array
    {

        if ($request->user())
            return [
                'breedersmap' => [
                    'breeder' => [
                        'view' => $request->user()->hasPermissionTo(Permission::READ_BREEDER->value),
                        'create' => $request->user()->hasPermissionTo(Permission::CREATE_BREEDER->value),
                        'update' => $request->user()->hasPermissionTo(Permission::UPDATE_BREEDER->value),
                        'delete' => $request->user()->hasPermissionTo(Permission::DELETE_BREEDER->value),
                    ],
                    'commodity' => [
                        'view' => $request->user()->hasPermissionTo(Permission::READ_COMMODITY->value),
                        'create' => $request->user()->hasPermissionTo(Permission::CREATE_COMMODITY->value),
                        'update' => $request->user()->hasPermissionTo(Permission::UPDATE_COMMODITY->value),
                        'delete' => $request->user()->hasPermissionTo(Permission::DELETE_COMMODITY->value),
                    ],
                ],
                'twgdb' => [
                    'expert' => [
                        'view' => $request->user()->hasPermissionTo(Permission::READ_TWG_EXPERT->value),
                        'create' => $request->user()->hasPermissionTo(Permission::CREATE_TWG_EXPERT->value),
                        'update' => $request->user()->hasPermissionTo(Permission::UPDATE_TWG_EXPERT->value),
                        'delete' => $request->user()->hasPermissionTo(Permission::DELETE_TWG_EXPERT->value),
                    ],
                    'product' => [
                        'view' => $request->user()->hasPermissionTo(Permission::READ_TWG_PRODUCT->value),
                        'create' => $request->user()->hasPermissionTo(Permission::CREATE_TWG_PRODUCT->value),
                        'update' => $request->user()->hasPermissionTo(Permission::UPDATE_TWG_PRODUCT->value),
                        'delete' => $request->user()->hasPermissionTo(Permission::DELETE_TWG_PRODUCT->value),
                    ],
                    'project' => [
                        'view' => $request->user()->hasPermissionTo(Permission::READ_TWG_PROJECT->value),
                        'create' => $request->user()->hasPermissionTo(Permission::CREATE_TWG_PROJECT->value),
                        'update' => $request->user()->hasPermissionTo(Permission::UPDATE_TWG_PROJECT->value),
                        'delete' => $request->user()->hasPermissionTo(Permission::DELETE_TWG_PROJECT->value),
                    ],
                    'services' => [
                        'view' => $request->user()->hasPermissionTo(Permission::READ_TWG_SERVICE->value),
                        'create' => $request->user()->hasPermissionTo(Permission::CREATE_TWG_SERVICE->value),
                        'update' => $request->user()->hasPermissionTo(Permission::UPDATE_TWG_SERVICE->value),
                        'delete' => $request->user()->hasPermissionTo(Permission::DELETE_TWG_SERVICE->value),
                    ],

                ]
            ];
        return [];
    }
}
