<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('company.view')) {
            abort(403, 'Unauthorized Access!');
        }

        $search = $request->get('search');

        $companies = Company::when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('backend.pages.companies.partials.table-rows', compact('companies'))->render(),
                'pagination' => view('backend.pages.companies.partials.pagination', compact('companies', 'search'))->render(),
                'summary'    => view('backend.pages.companies.partials.summary', compact('companies', 'search'))->render(),
            ]);
        }

        return view('backend.pages.companies.index', compact('companies', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('company.create')) {
            abort(403, 'Unauthorized Access!');
        }

        return view('backend.pages.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('company.create')) {
            abort(403, 'Unauthorized Access!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $company = Company::create($request->only('name'));
            return ['status' => 'success', 'data' => $company, 'msg' => 'Company has been Created!!'];
        } catch (\Exception $exception) {
            return ['status' => 'error', 'msg' => 'Failed Creating Company!!'];
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('company.edit')) {
            abort(403, 'Unauthorized Access!');
        }

        $company = Company::findOrFail($id);
        return view('backend.pages.companies.create', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('company.edit')) {
            abort(403, 'Unauthorized Access!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $company = Company::findOrFail($id);
        try {
            $company->update($request->only('name'));
            return ['status' => 'success', 'data' => $company, 'msg' => 'Company has been Updated!!'];
        } catch (\Exception $exception) {
            return ['status' => 'error', 'msg' => 'Failed Updating Company!!'];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('company.delete')) {
            abort(403, 'Unauthorized Access!');
        }

        $company = Company::find($id);

        if (!is_null($company)) {
            $company->delete();
            session()->flash('success', 'Company has been Deleted!!');
        } else {
            session()->flash('failed', 'Company could not be deleted!!');
        }

        return back();
    }
}
