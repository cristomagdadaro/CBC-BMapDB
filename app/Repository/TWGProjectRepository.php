<?php
namespace App\Repository;

use App\Models\TWGProject;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TWGProjectRepository extends BaseRepository
{
    /**
     * @var TWGProject
     */
    protected $model;

    public function __construct(TWGProject $model)
    {
        parent::__construct($model);
    }
}