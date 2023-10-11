<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\TWGBaseController;
use App\Http\Requests\TWGProjectRequest;
use App\Http\Resources\TWGResource;
use App\Models\TWGExpert;
use App\Models\TWGProduct;
use App\Models\TWGProject;
use App\Repository\TWGProjectRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TWGProjectsController extends TWGBaseController
{
    protected TWGProjectRepository $project;

    // constructor
    public function __construct(TWGProjectRepository $project)
    {
        $this->project = $project;
    }

    /**
     * Display a listing of the resource.
     */
    public function indexPage(): Response
    {
        return Inertia::render('Projects/TWG/TWGIndex');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Projects/TWG/TWGCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TWGProjectRequest $request): void
    {
        $this->project->create($request->validated())->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(TWGExpert $tWG)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Projects/TWG/TWGEdit');
    }

    public  function editData(Request $request): Model
    {
        return $this->project->find($request->id);
    }

    public function updateProject(TWGProjectRequest $request)
    {
        $temp = TWGProject::find($request->id);
        $temp->update($request->validated());
        //check if updated successfully
        if ($temp->save()) {
            return response()->json([
                'notification' => [
                    'id' => uniqid(),
                    'show' => true,
                    'type' => 'success',
                    'message' =>'Successfully updated project',
                ]
            ]);
        } else {
            return response()->json([
                'notification' => [
                    'id' => uniqid(),
                    'show' => true,
                    'type' => 'failed',
                    'message' =>'Failed to update project',
                ]
            ]);
        }
    }

    /**
     * Update Personal Information of the specified resource in storage.
     */
    public function updatePersonal(Request $request)
    {
        //validate request
        $request->validate([
            'fname' => 'required',
            'mname' => 'nullable',
            'lname' => 'required',
            'suffix' => 'nullable',
        ]);

        $data = TWGExpert::find($request->id);
        $data->fname = $request->fname;
        $data->mname = $request->mname;
        $data->lname = $request->lname;
        $data->suffix = $request->suffix;

        $data->save();
    }

    public function updateBackground(Request $request)
    {
        //validate request
        $request->validate([
            'position' => 'required',
            'educ_level' => 'required',
            'expertise' => 'required',
        ]);

        $data = TWGExpert::find($request->id);
        $data->position = $request->position;
        $data->educ_level = $request->educ_level;
        $data->expertise = $request->expertise;

        $data->save();
    }

    public function updateAccount(Request $request)
    {
        //validate request
        $request->validate([
            'email' => 'required',
            'mobile_no' => 'required',
            'password' => 'nullable',
            'password_confirmation' => 'required_with:password|same:password',
        ]);

        $data = TWGExpert::find($request->id);
        $data->email = $request->email;
        $data->mobile_no = $request->mobile_no;
        $data->password = $request->password;

        $data->save();
    }


    public function destroyProject(Request $request){
        $id = explode(',', $request->id);
        $temp = TWGProject::destroy($id);
        return response()->json([
            'notification' => [
                'id' => uniqid(),
                'show' => true,
                'type' => $temp?'success':'failed',
                'message' => $temp?'Successfully deleted '.$temp.' record/s':'Failed to delete record/s',
            ]
        ]);
    }

    public function tableProjects(Request $request): JsonResponse
    {

        $query = null;
        if(auth()->user()->role == 1)
            $query = TWGProject::select();
        else
            $query = TWGProject::where('twg_expert_id', auth()->user()->id)->select();
        $totalRecords = $query->count();
        if ($request->has('search')) {
            $search = $request->input('search');
            $searchBy = $request->input('search_by', 'id');
            $query->where(function ($q) use ($search, $searchBy) {
                if ($searchBy == '*') {
                    $q->where('id', 'like', '%' . $search . '%')
                        ->orWhere('title', 'like', '%' . $search . '%')
                        ->orWhere('objective', 'like', '%' . $search . '%')
                        ->orWhere('expected_output', 'like', '%' . $search . '%')
                        ->orWhere('project_leader', 'like', '%' . $search . '%')
                        ->orWhere('funding_agency', 'like', '%' . $search . '%')
                        ->orWhere('duration', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%');
                } else {
                    $q->where('twg_project.' . $searchBy, 'like', '%' . $search . '%');
                }
            });
        }

        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('sort_dir', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $paginator->items(),
            'totalCount' => $paginator->total(),
            'totalPages' => $paginator->lastPage(),
            'perPage' => $paginator->perPage(),
            'totalRecords' => $totalRecords,
        ]);
    }

    /*API Section of the class*/
    public function tableProducts(Request $request)
    {
        $query = TWGProduct::select();
        $totalRecords = $query->count();
        if ($request->has('search')) {
            $search = $request->input('search');
            $searchBy = $request->input('search_by', 'id');
            $query->where(function ($q) use ($search, $searchBy) {
                if ($searchBy == '*') {
                    $q->where('twg_expert_id', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('brand', 'like', '%' . $search . '%')
                        ->orWhere('purpose', 'like', '%' . $search . '%')
                        ->orWhere('cost', 'like', '%' . $search . '%');
                } else {
                    $q->where("twg_product." . $searchBy, 'like', '%' . $search . '%');
                }
            });
        }

        // Handle sorting
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('sort_dir', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $paginator->items(),
            'totalCount' => $paginator->total(),
            'totalPages' => $paginator->lastPage(),
            'perPage' => $paginator->perPage(),
            'totalRecords' => $totalRecords,
        ]);
    }

    public function delete(Request $request){
        $id = explode(',', $request->id);
        $temp = TWGExpert::destroy($id);
        return response()->json([
            'notification' => [
                'id' => uniqid(),
                'show' => true,
                'type' => $temp?'success':'failed',
                'message' => $temp?'Successfully deleted '.$temp.' Fee record/s':'Failed to delete Fee record with id '. $request->id,
            ]
        ]);
    }

    public function exportProject(){
        if(auth()->user()->role == 1) //returns all projects when admin
            return TWGResource::collection(TWGProject::all());
        return TWGResource::collection(TWGProject::where('twg_expert_id', auth()->user()->id)->get());
    }
}
