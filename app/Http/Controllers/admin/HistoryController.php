<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Advocate;
use App\Models\CaseItem;
use App\Models\Company;
use App\Models\Court;
use App\Models\Division;
use App\Models\History;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        $this->user = $this->user ?: Auth::guard('admin')->user();
        if (is_null($this->user) || !$this->user->can('history.view')) {
            abort(403, 'Unauthorized Access!');
        }

        // Fetch dropdown lists for filters
        $projects  = Project::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $courts    = Court::orderBy('name')->get();
        $types     = Type::orderBy('name')->get();
        $advocates = Advocate::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();

        $query = History::with([
            'cases.projects',
            'cases.divisions',
            'cases.types',
            'cases.courts',
            'cases.advocates',
            'cases.companies',
        ])->select('id', 'case_id', 'date', 'past_date', 'next_date', 'status', 'is_nispotti', 'nispotti_date');

        // 1. Filter: Case No (text / partial match)
        if ($request->filled('case_no')) {
            $caseNo = trim($request->case_no);
            $query->whereHas('cases', function ($q) use ($caseNo) {
                $q->where('case_no', 'like', "%{$caseNo}%");
            });
        }

        // 2. Filter: Project
        if ($request->filled('project')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('project', $request->project);
            });
        }

        // 3. Filter: Division
        if ($request->filled('division')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('division', $request->division);
            });
        }

        // 4. Filter: Case Type
        if ($request->filled('case_type')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('case_type', $request->case_type);
            });
        }

        // 5. Filter: Court Name
        if ($request->filled('court_name')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('court_name', $request->court_name);
            });
        }

        // 6. Filter: Advocate Name
        if ($request->filled('adv_name')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('adv_name', $request->adv_name);
            });
        }

        // 7. Filter: Company
        if ($request->filled('company_id')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        // 8. Filter: Parties Name (পক্ষদের নাম)
        if ($request->filled('parties_name')) {
            $parties = trim($request->parties_name);
            $query->whereHas('cases', function ($q) use ($parties) {
                $q->where('parties_name', 'like', "%{$parties}%");
            });
        }

        // 9. Filter: Status / Activity (নির্ধারিত কার্যক্রম)
        if ($request->filled('status')) {
            $status = trim($request->status);
            $query->where('status', 'like', "%{$status}%");
        }

        // 10. Filter: Next Date Range (ধার্য তারিখ সীমা)
        if ($request->filled('date_from')) {
            $query->whereDate('next_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('next_date', '<=', $request->date_to);
        }

        // 11. Filter: Case State (মামলার অবস্থা)
        if ($request->filled('nispotti_status')) {
            if ($request->nispotti_status === 'active') {
                $query->where('is_nispotti', false);
            } elseif ($request->nispotti_status === 'overdue') {
                $query->where('is_nispotti', false)
                      ->whereDate('next_date', '<', now()->toDateString());
            } elseif ($request->nispotti_status === 'upcoming') {
                $query->where('is_nispotti', false)
                      ->whereDate('next_date', '>=', now()->toDateString());
            } elseif ($request->nispotti_status === 'nispotti') {
                $query->where('is_nispotti', true);
            }
        }

        // 12. Filter: Global Search Keyword (যেকোনো ফিল্ডে সাধারণ অনুসন্ধান)
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('status', 'like', "%{$keyword}%")
                  ->orWhereHas('cases', function ($caseQ) use ($keyword) {
                      $caseQ->where('case_no', 'like', "%{$keyword}%")
                            ->orWhere('parties_name', 'like', "%{$keyword}%")
                            ->orWhere('case_details', 'like', "%{$keyword}%")
                            ->orWhere('case_subject', 'like', "%{$keyword}%");
                  });
            });
        }

        // Get all filtered histories, ordered by next_date (oldest expired first, then upcoming)
        $histories = $query->orderBy('next_date', 'asc')->get();

        // Count old/expired histories (excluding settled ones)
        $oldHistoriesCount = History::where('is_nispotti', false)
                                    ->whereDate('next_date', '<', now()->toDateString())->count();

        // Check if any filter is active (only for the active filter fields)
        $hasActiveFilter = $request->anyFilled([
            'project', 'division', 'case_type', 'court_name',
            'adv_name', 'company_id', 'date_from', 'date_to'
        ]);

        // Build active filter summary array for UI badges
        $appliedFilters = [];
        if ($request->filled('project')) {
            $p = $projects->firstWhere('id', $request->project);
            $appliedFilters['প্রজেক্ট'] = $p ? $p->name : $request->project;
        }
        if ($request->filled('division')) {
            $d = $divisions->firstWhere('id', $request->division);
            $appliedFilters['বিভাগ'] = $d ? $d->name : $request->division;
        }
        if ($request->filled('case_type')) {
            $t = $types->firstWhere('id', $request->case_type);
            $appliedFilters['মামলার ধরন'] = $t ? $t->name : $request->case_type;
        }
        if ($request->filled('court_name')) {
            $c = $courts->firstWhere('id', $request->court_name);
            $appliedFilters['আদালত'] = $c ? $c->name : $request->court_name;
        }
        if ($request->filled('adv_name')) {
            $a = $advocates->firstWhere('id', $request->adv_name);
            $appliedFilters['আইনজীবী'] = $a ? $a->name : $request->adv_name;
        }
        if ($request->filled('company_id')) {
            $comp = $companies->firstWhere('id', $request->company_id);
            $appliedFilters['কোম্পানি'] = $comp ? $comp->name : $request->company_id;
        }
        if ($request->filled('date_from')) {
            $appliedFilters['তারিখ হতে'] = $request->date_from;
        }
        if ($request->filled('date_to')) {
            $appliedFilters['তারিখ পর্যন্ত'] = $request->date_to;
        }

        return view('backend.pages.histories.index', compact(
            'histories',
            'oldHistoriesCount',
            'projects',
            'divisions',
            'courts',
            'types',
            'advocates',
            'companies',
            'hasActiveFilter',
            'appliedFilters'
        ));
    }

    public function nispottiHistories(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('history.view')) {
            abort(403, 'Unauthorized Access!');
        }

        $query = History::with('cases:id,case_no,division,project,case_type,court_name,adv_name,parties_name')
                        ->select('id', 'case_id', 'date', 'past_date', 'next_date', 'status', 'is_nispotti', 'nispotti_date')
                        ->where('is_nispotti', true);

        if ($request->filled('year')) {
            $query->whereYear('nispotti_date', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereMonth('nispotti_date', $request->month);
        }

        $histories = $query->orderBy('nispotti_date', 'desc')->get();

        // Build available years from all nispotti records for the filter dropdown
        $years = History::where('is_nispotti', true)
                        ->whereNotNull('nispotti_date')
                        ->selectRaw('YEAR(nispotti_date) as year')
                        ->distinct()
                        ->orderBy('year', 'desc')
                        ->pluck('year');

        return view('backend.pages.histories.nispotti', compact('histories', 'years'));
    }

    public function oldHistories(Request $request)
    {
        $this->user = $this->user ?: Auth::guard('admin')->user();
        if (is_null($this->user) || !$this->user->can('history.view')) {
            abort(403, 'Unauthorized Access!');
        }

        // Fetch dropdown lists for filters
        $projects  = Project::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $courts    = Court::orderBy('name')->get();
        $types     = Type::orderBy('name')->get();
        $advocates = Advocate::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();

        // Get old/expired histories (next_date < today)
        $query = History::with([
            'cases.projects',
            'cases.divisions',
            'cases.types',
            'cases.courts',
            'cases.advocates',
            'cases.companies',
        ])->select('id', 'case_id', 'date', 'past_date', 'next_date', 'status', 'is_nispotti')
          ->where('is_nispotti', false)
          ->whereDate('next_date', '<', now()->toDateString());

        // 1. Filter: Project
        if ($request->filled('project')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('project', $request->project);
            });
        }

        // 2. Filter: Division
        if ($request->filled('division')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('division', $request->division);
            });
        }

        // 3. Filter: Case Type
        if ($request->filled('case_type')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('case_type', $request->case_type);
            });
        }

        // 4. Filter: Court Name
        if ($request->filled('court_name')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('court_name', $request->court_name);
            });
        }

        // 5. Filter: Advocate Name
        if ($request->filled('adv_name')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('adv_name', $request->adv_name);
            });
        }

        // 6. Filter: Company
        if ($request->filled('company_id')) {
            $query->whereHas('cases', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        // 7. Filter: Next Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('next_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('next_date', '<=', $request->date_to);
        }

        $histories = $query->orderBy('next_date', 'asc')->get();

        // Check if any filter is active
        $hasActiveFilter = $request->anyFilled([
            'project', 'division', 'case_type', 'court_name',
            'adv_name', 'company_id', 'date_from', 'date_to'
        ]);

        // Build active filter summary array for UI badges
        $appliedFilters = [];
        if ($request->filled('project')) {
            $p = $projects->firstWhere('id', $request->project);
            $appliedFilters['প্রজেক্ট'] = $p ? $p->name : $request->project;
        }
        if ($request->filled('division')) {
            $d = $divisions->firstWhere('id', $request->division);
            $appliedFilters['বিভাগ'] = $d ? $d->name : $request->division;
        }
        if ($request->filled('case_type')) {
            $t = $types->firstWhere('id', $request->case_type);
            $appliedFilters['মামলার ধরন'] = $t ? $t->name : $request->case_type;
        }
        if ($request->filled('court_name')) {
            $c = $courts->firstWhere('id', $request->court_name);
            $appliedFilters['আদালত'] = $c ? $c->name : $request->court_name;
        }
        if ($request->filled('adv_name')) {
            $a = $advocates->firstWhere('id', $request->adv_name);
            $appliedFilters['আইনজীবী'] = $a ? $a->name : $request->adv_name;
        }
        if ($request->filled('company_id')) {
            $comp = $companies->firstWhere('id', $request->company_id);
            $appliedFilters['কোম্পানি'] = $comp ? $comp->name : $request->company_id;
        }
        if ($request->filled('date_from')) {
            $appliedFilters['তারিখ হতে'] = $request->date_from;
        }
        if ($request->filled('date_to')) {
            $appliedFilters['তারিখ পর্যন্ত'] = $request->date_to;
        }

        return view('backend.pages.histories.old', compact(
            'histories',
            'projects',
            'divisions',
            'courts',
            'types',
            'advocates',
            'companies',
            'hasActiveFilter',
            'appliedFilters'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('history.view')) {
            abort(403, 'Unauthorized Access!');
        }
        // Optimize: Only fetch recent histories and only needed case fields
        $histories = History::with('cases:id,case_no')
                            ->select('id', 'case_id', 'next_date')
                            ->orderBy('id', 'desc')
                            ->limit(100)
                            ->get();
        // Exclude cases that already have at least one history entry
        $usedCaseIds = History::pluck('case_id')->unique()->toArray();
        $cases = CaseItem::select('id', 'case_no', 'parties_name')
                         ->whereNotIn('id', $usedCaseIds)
                         ->orderBy('id', 'desc')
                         ->get();
        return view('backend.pages.histories.create', compact('histories','cases'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('history.create')) {
            abort(403, 'Unauthorized Access!');
        }
        try {
            $isNispotti = $request->has('is_nispotti') ? 1 : 0;

            $rules = [
                'case_id'   => 'required|integer',
                'date'      => 'required|date',
                'past_date' => 'required|date',
                'status'    => 'nullable|string|max:255',
            ];
            if ($isNispotti) {
                $rules['nispotti_date'] = 'required|date';
            } else {
                $rules['next_date'] = 'required|date';
            }
            $validated = $request->validate($rules);

            $validated['is_nispotti'] = $isNispotti;
            if ($isNispotti) {
                $validated['next_date'] = null;
            } else {
                $validated['nispotti_date'] = null;
            }

            $history = History::create($validated);
            return ['status' => 'success', 'data' => $history, 'msg' => 'Case History has been Created !!'];

        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'msg' => implode(' ', $e->validator->errors()->all())], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Creating Case History !!'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (is_null($this->user) || !$this->user->can('history.view')) {
            abort(403, 'Unauthorized Access!');
        }
        $history=History::findOrFail($id);

        return view('backend.pages.histories.show', compact('history'));

        }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('history.edit')) {
            abort(403, 'Unauthorized Access!');
        }
        $history=History::findOrFail($id);

        return view('backend.pages.histories.create', compact('history'));
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
        if (is_null($this->user) || !$this->user->can('history.edit')) {
            abort(403, 'Unauthorized Access!');
        }

        $history = History::findOrFail($id);
        try {
            $isNispotti = $request->has('is_nispotti') ? 1 : 0;

            $rules = [
                'date'      => 'required|date',
                'past_date' => 'required|date',
                'status'    => 'nullable|string|max:255',
            ];
            if ($isNispotti) {
                $rules['nispotti_date'] = 'required|date';
            } else {
                $rules['next_date'] = 'required|date';
            }
            $validated = $request->validate($rules);

            $validated['is_nispotti'] = $isNispotti;
            if ($isNispotti) {
                $validated['next_date'] = null;
            } else {
                $validated['nispotti_date'] = null;
            }

            $history->update($validated);
            return ['status' => 'success', 'data' => $history, 'msg' => 'Case History has been Updated !!'];

        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'msg' => implode(' ', $e->validator->errors()->all())], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Updating Case History !!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('history.delete')) {
            abort(403, 'Unauthorized Access!');
        }
        $history = History::find($id);

        if(!is_null($history)){
            $history->delete();
            session()->flash('success', 'Case History has been Deleted!!');
        }else {
            session()->flash('failed', 'Case History could not be deleted!!');
        }
        return back();

    }
}
