<?php

namespace App\Http\Interfaces;

interface BaseControllerInterface
{
    public function index();

    public function store();

    public function show($id);

    public function update($id);

    public function destroy($id);
    
    public function multiDestroy();
}