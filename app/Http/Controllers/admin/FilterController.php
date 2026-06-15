<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Advocate;
use App\Models\Company;
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
        $companies=Company::orderBy('name')->get();
        $histories = History::with('cases.companies')->orderBy('next_date', 'asc')->get();
        $appliedFilters = [];

        return view('backend.pages.reports.filters.index',compact('projects','divisions','courts','types','advocates','companies','histories','appliedFilters'));
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
        $companies = Company::orderBy('name')->get();

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
        if ($request->filled('company_id')) {
            $query->whereHas('cases', fn($q) => $q->where('company_id', $request->company_id));
        }

        // নিষ্পত্তি filter
        if ($request->nispotti_status === 'nispotti') {
            $query->where('is_nispotti', true);
        } elseif ($request->nispotti_status === 'active') {
            $query->where('is_nispotti', false);
        }

        $histories = $query->with('cases.companies')->orderBy('next_date', 'asc')->get();

        // Build applied filter labels for print header
        $appliedFilters = [];
        if ($request->filled('project')) {
            $p = Project::find($request->project);
            if ($p) $appliedFilters['প্রজেক্ট'] = $p->name;
        }
        if ($request->filled('division')) {
            $d = Division::find($request->division);
            if ($d) $appliedFilters['বিভাগ'] = $d->name;
        }
        if ($request->filled('case_type')) {
            $t = Type::find($request->case_type);
            if ($t) $appliedFilters['মামলার ধরন'] = $t->name;
        }
        if ($request->filled('court_name')) {
            $c = Court::find($request->court_name);
            if ($c) $appliedFilters['আদালত'] = $c->name;
        }
        if ($request->filled('adv_name')) {
            $a = Advocate::find($request->adv_name);
            if ($a) $appliedFilters['আইনজীবী'] = $a->name;
        }
        if ($request->filled('company_id')) {
            $co = Company::find($request->company_id);
            if ($co) $appliedFilters['কোম্পানি'] = $co->name;
        }
        if ($request->nispotti_status === 'nispotti') {
            $appliedFilters['অবস্থা'] = 'শুধু নিষ্পত্তি মামলা';
        } elseif ($request->nispotti_status === 'active') {
            $appliedFilters['অবস্থা'] = 'চলমান মামলা';
        }

        return view('backend.pages.reports.filters.index', compact('projects', 'divisions', 'courts', 'types', 'advocates', 'companies', 'histories', 'appliedFilters'));
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
