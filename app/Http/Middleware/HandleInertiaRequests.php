<?php

namespace App\Http\Middleware;

use Modules\PbMap\Enums\Permissions as PbMapPermissions;
use Modules\TwgDb\Enums\Permissions as TwgDbPermissions;
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
                        'view' => $request->user()->hasPermissionTo(PbMapPermissions::READ_BREEDER->value),
                        'create' => $request->user()->hasPermissionTo(PbMapPermissions::CREATE_BREEDER->value),
                        'update' => $request->user()->hasPermissionTo(PbMapPermissions::UPDATE_BREEDER->value),
                        'delete' => $request->user()->hasPermissionTo(PbMapPermissions::DELETE_BREEDER->value),
                    ],
                    'commodity' => [
                        'view' => $request->user()->hasPermissionTo(PbMapPermissions::READ_COMMODITY->value),
                        'create' => $request->user()->hasPermissionTo(PbMapPermissions::CREATE_COMMODITY->value),
                        'update' => $request->user()->hasPermissionTo(PbMapPermissions::UPDATE_COMMODITY->value),
                        'delete' => $request->user()->hasPermissionTo(PbMapPermissions::DELETE_COMMODITY->value),
                    ],
                ],
                'twgdb' => [
                    'expert' => [
                        'view' => $request->user()->hasPermissionTo(TwgDbPermissions::READ_TWG_EXPERT->value),
                        'create' => $request->user()->hasPermissionTo(TwgDbPermissions::CREATE_TWG_EXPERT->value),
                        'update' => $request->user()->hasPermissionTo(TwgDbPermissions::UPDATE_TWG_EXPERT->value),
                        'delete' => $request->user()->hasPermissionTo(TwgDbPermissions::DELETE_TWG_EXPERT->value),
                    ],
                    'product' => [
                        'view' => $request->user()->hasPermissionTo(TwgDbPermissions::READ_TWG_PRODUCT->value),
                        'create' => $request->user()->hasPermissionTo(TwgDbPermissions::CREATE_TWG_PRODUCT->value),
                        'update' => $request->user()->hasPermissionTo(TwgDbPermissions::UPDATE_TWG_PRODUCT->value),
                        'delete' => $request->user()->hasPermissionTo(TwgDbPermissions::DELETE_TWG_PRODUCT->value),
                    ],
                    'project' => [
                        'view' => $request->user()->hasPermissionTo(TwgDbPermissions::READ_TWG_PROJECT->value),
                        'create' => $request->user()->hasPermissionTo(TwgDbPermissions::CREATE_TWG_PROJECT->value),
                        'update' => $request->user()->hasPermissionTo(TwgDbPermissions::UPDATE_TWG_PROJECT->value),
                        'delete' => $request->user()->hasPermissionTo(TwgDbPermissions::DELETE_TWG_PROJECT->value),
                    ],
                    'services' => [
                        'view' => $request->user()->hasPermissionTo(TwgDbPermissions::READ_TWG_SERVICE->value),
                        'create' => $request->user()->hasPermissionTo(TwgDbPermissions::CREATE_TWG_SERVICE->value),
                        'update' => $request->user()->hasPermissionTo(TwgDbPermissions::UPDATE_TWG_SERVICE->value),
                        'delete' => $request->user()->hasPermissionTo(TwgDbPermissions::DELETE_TWG_SERVICE->value),
                    ],

                ]
            ];
        return [];
    }
}
