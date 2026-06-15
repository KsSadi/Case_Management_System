<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AppellateCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppellateCaseController extends Controller
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

        $cases = AppellateCase::orderBy('id', 'desc')->get();
        return view('backend.pages.appellate.index', compact('cases'));
    }

    public function create()
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        return view('backend.pages.appellate.create');
    }

    public function store(Request $request)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $request->validate([
            'case_no'      => 'required|string|max:255',
            'parties_name' => 'required|string|max:255',
            'first_order'  => 'nullable|string',
            'last_order'   => 'nullable|string',
        ]);

        try {
            $case = AppellateCase::create($request->all());
            return ['status' => 'success', 'data' => $case, 'msg' => 'Appellate Case has been Created !!'];
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Creating Appellate Case !!'], 500);
        }
    }

    public function show($id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $case = AppellateCase::findOrFail($id);
        return view('backend.pages.appellate.show', compact('case'));
    }

    public function edit($id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $case = AppellateCase::findOrFail($id);
        return view('backend.pages.appellate.create', compact('case'));
    }

    public function update(Request $request, $id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $request->validate([
            'case_no'      => 'required|string|max:255',
            'parties_name' => 'required|string|max:255',
            'first_order'  => 'nullable|string',
            'last_order'   => 'nullable|string',
        ]);

        $case = AppellateCase::findOrFail($id);

        try {
            $case->update($request->all());
            return ['status' => 'success', 'data' => $case, 'msg' => 'Appellate Case has been Updated !!'];
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Updating Appellate Case !!'], 500);
        }
    }

    public function destroy($id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $case = AppellateCase::find($id);

        if (!is_null($case)) {
            $case->delete();
            session()->flash('success', 'Appellate Case has been Deleted!!');
        } else {
            session()->flash('failed', 'Appellate Case could not be deleted!!');
        }
        return back();
    }
}
