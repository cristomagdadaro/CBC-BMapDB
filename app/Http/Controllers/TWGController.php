<?php

namespace App\Http\Controllers;

use App\Http\Resources\TWGResource;
use App\Models\TWGExpert;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TWGController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Projects/TWG/TWGDatabase', [
            'twg_experts' => TWGExpert::with('twg_projects')->get(),
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
            'twg_expert' => TWGExpert::with('twg_projects')->with('twg_services')->with('twg_products')->find($request->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TWGExpert $tWG)
    {
        //
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

    public function export(){
        return TWGResource::collection(TWGExpert::all());
    }
}
