<?php

namespace App\Http\Interfaces;
;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

interface BaseControllerInterface
{
    /**
     * @throws AuthorizationException
     */
    public function _index($request);
    /**
     * @throws AuthorizationException
     */
    public function _store($request);
    /**
     * @throws AuthorizationException
     */
    public function _show($request, int $id);
    /**
     * @throws AuthorizationException
     */
    public function _update($request, int $id);
    /**
     * @throws AuthorizationException
     */
    public function _destroy(int $id);
    /**
     * @throws AuthorizationException
     */
    public function _multiDestroy($request);
}
