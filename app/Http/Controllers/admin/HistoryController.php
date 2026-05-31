<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CaseItem;
use App\Models\History;
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
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('history.view')) {
            abort(403, 'Unauthorized Access!');
        }
        // Get all histories, ordered by next_date (oldest expired first, then upcoming)
        $histories = History::with('cases:id,case_no,division,project,case_type,court_name,adv_name,company_id')
                            ->select('id', 'case_id', 'date', 'past_date', 'next_date', 'status', 'is_nispotti')
                            ->orderBy('next_date', 'asc')
                            ->get();
        
        // Count old/expired histories (excluding settled ones)
        $oldHistoriesCount = History::where('is_nispotti', false)
                                    ->whereDate('next_date', '<', now()->toDateString())->count();
        
        return view('backend.pages.histories.index', compact('histories', 'oldHistoriesCount'));
    }

    public function nispottiHistories(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('history.view')) {
            abort(403, 'Unauthorized Access!');
        }

        $query = History::with('cases:id,case_no,division,project,case_type,court_name,adv_name')
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

    public function oldHistories()
    {
        if (is_null($this->user) || !$this->user->can('history.view')) {
            abort(403, 'Unauthorized Access!');
        }
        // Get old/expired histories (next_date < today)
        $histories = History::with('cases:id,case_no,division,project,case_type,court_name,adv_name')
                            ->select('id', 'case_id', 'date', 'past_date', 'next_date', 'status', 'is_nispotti')
                            ->where('is_nispotti', false)
                            ->whereDate('next_date', '<', now()->toDateString())
                            ->orderBy('next_date', 'desc')
                            ->get();
        
        return view('backend.pages.histories.old', compact('histories'));
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
