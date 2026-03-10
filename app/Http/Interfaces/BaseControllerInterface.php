<?php

namespace App\Http\Interfaces;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

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

    /**
     * api for selection option field. returns id and name only
     */
    public function _selection($request);
}
