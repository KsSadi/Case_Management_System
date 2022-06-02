<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class CourtController extends Controller
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
        if (is_null($this->user) || !$this->user->can('court.view')) {
            abort(403, 'Unauthorized Access!');
        }
        $courts= Court::all();
        return view('backend.pages.courts.index', compact('courts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('court.create')) {
            abort(403, 'Unauthorized Access!');
        }
        $courts= Court::all();

        return view('backend.pages.courts.create', compact('courts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('court.create')) {
            abort(403, 'Unauthorized Access!');
        }

        try {
            $court = Court::create($request->all());
            return ['status'=>'success','data'=>$court,'msg'=>' Court Name has been Created !!'];

            return redirect() ->back()->with('success',' Case Type has been Created !!');

        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Creating Court !!');
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
        if (is_null($this->user) || !$this->user->can('court.edit')) {
            abort(403, 'Unauthorized Access!');
        }
        $court= Court::findOrFail($id);
        return view('backend.pages.courts.create', compact('court'));
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
        if (is_null($this->user) || !$this->user->can('court.edit')) {
            abort(403, 'Unauthorized Access!');
        }
        $court = Court::findOrFail($id);
        try {
            $court =$court-> update($request->all());
            return ['status'=>'success','data'=>$court,'msg'=>' Court has been Updated !!'];

        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Updating Advocate!!');
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
        if (is_null($this->user) || !$this->user->can('court.delete')) {
            abort(403, 'Unauthorized Access!');
        }

        $court =Court::find($id);
        if(!is_null($court)){
            $court->delete();
            session()->flash('success', 'Court has been Deleted!!');
        }else {
            session()->flash('failed', 'Court could not be deleted!!');
        }
        return back();

    }
}
