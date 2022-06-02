<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class CaseDivisionController extends Controller
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
        //
        if (is_null($this->user) || !$this->user->can('division.view')) {
            abort(403, 'Unauthorized Access!');
        }

        $divisions=Division::all();
        return view('backend.pages.divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('division.create')) {
            abort(403, 'Unauthorized Access!');
        }

        $divisions=Division::all();
        return view('backend.pages.divisions.create', compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('division.create')) {
            abort(403, 'Unauthorized Access!');
        }

        try {
            $division = Division::create($request->all());
            return ['status'=>'success','data'=>$division,'msg'=>' Division has been Created !!'];


        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Creating Advocate !!');
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
        if (is_null($this->user) || !$this->user->can('division.create')) {
            abort(403, 'Unauthorized Access!');
        }

        $division=Division::findOrFail($id);
        return view('backend.pages.divisions.create', compact('division'));
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
        if (is_null($this->user) || !$this->user->can('division.edit')) {
            abort(403, 'Unauthorized Access!');
        }
        $division = Division::findOrFail($id);
        try {
            $division =$division-> update($request->all());
            return ['status'=>'success','data'=>$division,'msg'=>' Division has been Updated !!'];

        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Updating Division!!');
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
        //
        if (is_null($this->user) || !$this->user->can('division.delete')) {
            abort(403, 'Unauthorized Access!');
        }
        $division = Division::find($id);
        if(!is_null($division)){
            $division->delete();
            session()->flash('success', 'Case Division has been Deleted!!');
        }else {
            session()->flash('failed', 'Case Division could not be deleted!!');
        }
        return back();

    }
}
