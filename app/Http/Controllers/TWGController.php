<?php

namespace App\Http\Controllers;

use App\Http\Requests\TWGProjectRequest;
use App\Http\Resources\TWGResource;
use App\Models\TWGExpert;
use App\Models\TWGProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TWGController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Projects/TWG/TWGIndex', [
            //'twg_experts' => TWGExpert::with('twg_projects')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Projects/TWG/TWGCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function twgprojectstore(TWGProjectRequest $request)
    {
        $twg_project = new TWGProject(
            $request->validated()
        );

        $twg_project->save();
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
    public function edit(Request $request)
    {
        return Inertia::render('Projects/TWG/TWGEdit', [
            //'twg_expert' => TWGExpert::with('twg_projects')->with('twg_services')->with('twg_products')->find($request->id),
        ]);
    }

    public  function editdata(Request $request){
        return TWGProject::where('id', $request->id)->get()->first();
    }

    public function updateProject(TWGProjectRequest $request)
    {
        $twg_project = TWGProject::find($request->id);
        $twg_project->update($request->validated());
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





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request){
        $data = TWGProject::find($request->id);
        $data->destroy();
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

    public function tableprojects(Request $request): JsonResponse
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

    /*API Section of the class*/
    public function table(Request $request)
    {
        $query = TWGExpert::select('id','fname', 'mname', 'lname', 'suffix', 'position', 'educ_level', 'expertise', 'email', 'mobile_no');
        $totalRecords = $query->count();
        if ($request->has('search')) {
            $search = $request->input('search');
            $searchBy = $request->input('search_by', 'id');
            $query->where(function ($q) use ($search, $searchBy) {
                if ($searchBy == '*') {
                    $q->where('id', 'like', '%' . $search . '%')
                        ->orWhere('fname', 'like', '%' . $search . '%')
                        ->orWhere('mname', 'like', '%' . $search . '%')
                        ->orWhere('lname', 'like', '%' . $search . '%')
                        ->orWhere('suffix', 'like', '%' . $search . '%')
                        ->orWhere('position', 'like', '%' . $search . '%')
                        ->orWhere('educ_level', 'like', '%' . $search . '%')
                        ->orWhere('expertise', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('mobile_no', 'like', '%' . $search . '%');

                } else {
                    $q->where('twg_expert.' . $searchBy, 'like', '%' . $search . '%');
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

    public function exportproject(){
        if(auth()->user()->role == 1) //returns all projects when admin
            return TWGResource::collection(TWGProject::all());
        return TWGResource::collection(TWGProject::where('twg_expert_id', auth()->user()->id)->get());
    }
}
