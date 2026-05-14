<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Advocate;
use App\Models\Court;
use App\Models\Division;
use App\Models\History;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('report.date')) {
            abort(403, 'Unauthorized Access!');
        }

        $projects= Project::all();
        $divisions= Division::all();
        $courts=Court::all();
        $types= Type::all();
        $advocates=Advocate::all();
        $histories = History::all();

        return view('backend.pages.reports.filters.index',compact('projects','divisions','courts','types','advocates','histories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('report.date')) {
            abort(403, 'Unauthorized Access!');
        }

        $projects  = Project::all();
        $divisions = Division::all();
        $courts    = Court::all();
        $types     = Type::all();
        $advocates = Advocate::all();

        $query = History::query();

        if ($request->filled('project')) {
            $query->whereHas('cases', fn($q) => $q->where('project', $request->project));
        }
        if ($request->filled('division')) {
            $query->whereHas('cases', fn($q) => $q->where('division', $request->division));
        }
        if ($request->filled('case_type')) {
            $query->whereHas('cases', fn($q) => $q->where('case_type', $request->case_type));
        }
        if ($request->filled('court_name')) {
            $query->whereHas('cases', fn($q) => $q->where('court_name', $request->court_name));
        }
        if ($request->filled('adv_name')) {
            $query->whereHas('cases', fn($q) => $q->where('adv_name', $request->adv_name));
        }

        $histories = $query->get();

        return view('backend.pages.reports.filters.index', compact('projects', 'divisions', 'courts', 'types', 'advocates', 'histories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
