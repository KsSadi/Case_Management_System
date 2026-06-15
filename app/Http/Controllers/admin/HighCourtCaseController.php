<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HighCourtCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HighCourtCaseController extends Controller
{
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
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $cases = HighCourtCase::orderBy('id', 'desc')->get();
        return view('backend.pages.high_court.index', compact('cases'));
    }

    public function create()
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        return view('backend.pages.high_court.create');
    }

    public function store(Request $request)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $request->validate([
            'case_no'      => 'required|string|max:255',
            'parties_name' => 'required|string|max:255',
            'case_details' => 'nullable|string',
            'first_order'  => 'nullable|string',
            'last_order'   => 'nullable|string',
        ]);

        try {
            $case = HighCourtCase::create($request->all());
            return ['status' => 'success', 'data' => $case, 'msg' => 'High Court Case has been Created !!'];
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Creating High Court Case !!'], 500);
        }
    }

    public function show($id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $case = HighCourtCase::findOrFail($id);
        return view('backend.pages.high_court.show', compact('case'));
    }

    public function edit($id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $case = HighCourtCase::findOrFail($id);
        return view('backend.pages.high_court.create', compact('case'));
    }

    public function update(Request $request, $id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $request->validate([
            'case_no'      => 'required|string|max:255',
            'parties_name' => 'required|string|max:255',
            'case_details' => 'nullable|string',
            'first_order'  => 'nullable|string',
            'last_order'   => 'nullable|string',
        ]);

        $case = HighCourtCase::findOrFail($id);

        try {
            $case->update($request->all());
            return ['status' => 'success', 'data' => $case, 'msg' => 'High Court Case has been Updated !!'];
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Updating High Court Case !!'], 500);
        }
    }

    public function destroy($id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $case = HighCourtCase::find($id);

        if (!is_null($case)) {
            $case->delete();
            session()->flash('success', 'High Court Case has been Deleted!!');
        } else {
            session()->flash('failed', 'High Court Case could not be deleted!!');
        }
        return back();
    }
}
