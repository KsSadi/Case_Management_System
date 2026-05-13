<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Advocate;
use App\Models\CaseItem;
use App\Models\Court;
use App\Models\Division;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class CaseController extends Controller
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
        if (is_null($this->user) || !$this->user->can('case.view')) {
            abort(403, 'Unauthorized Access!');
        }

        // Optimize: Order by latest and load efficiently
        $cases = CaseItem::orderBy('id', 'desc')->get();

        return view('backend.pages.cases.index', compact('cases'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('case.create')) {
            abort(403, 'Unauthorized Access!');
        }

        // Optimize: Only select needed columns for dropdowns
        $cases = CaseItem::select('id', 'case_no')->orderBy('id', 'desc')->limit(100)->get();
        $projects = Project::select('id', 'name')->orderBy('name')->get();
        $divisions = Division::select('id', 'name')->orderBy('name')->get();
        $courts = Court::select('id', 'name')->orderBy('name')->get();
        $types = Type::select('id', 'name')->orderBy('name')->get();
        $advocates = Advocate::select('id', 'name')->orderBy('name')->get();

        return view('backend.pages.cases.create', compact('cases','projects','divisions','types','courts','advocates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('case.create')) {
            abort(403, 'Unauthorized Access!');
        }

        try {
            $case=CaseItem::create($request->all());
            return ['status'=>'success','data'=>$case,'msg'=>' Case Item has been Created !!'];


        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Creating Case Item !!');
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
        //Check and guard the permission
        if (is_null($this->user) || !$this->user->can('case.view')) {
            abort(403, 'Unauthorized Access!');
        }
        $case = CaseItem::findOrFail($id);

        return view('backend.pages.cases.show',compact('case'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('case.view')) {
            abort(403, 'Unauthorized Access!');
        }

        $case = CaseItem::findOrFail($id);

        return view('backend.pages.cases.create', compact('case'));
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
        if (is_null($this->user) || !$this->user->can('case.edit')) {
            abort(403, 'Unauthorized Access!');
        }
        $case= CaseItem::findOrFail($id);
        try {
            $case =$case-> update($request->all());
            return ['status'=>'success','data'=>$case,'msg'=>' Case item has been Updated !!'];

        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Updating Case Item!!');

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
        if (is_null($this->user) || !$this->user->can('case.delete')) {
            abort(403, 'Unauthorized Access!');
        }
        $case=CaseItem::find($id);

        if(!is_null($case)){
            $case->delete();
            session()->flash('success', 'Case has been Deleted!!');
        }else {
            session()->flash('failed', 'Case could not be deleted!!');
        }
        return back();
    }
}
