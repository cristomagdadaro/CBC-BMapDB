<?php
namespace App\Repository;

use App\Models\TWGProject;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TWGProjectRepositoryAbstract extends AbstractBaseRepository
{
    /**
     * @var TWGProject
     */
    public Model $model;

    public function __construct(TWGProject $model)
    {
        parent::__construct($model);
    }
}
