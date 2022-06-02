<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Advocate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class AdvocateController extends Controller
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
        if (is_null($this->user) || !$this->user->can('advocate.view')) {
            abort(403, 'Unauthorized Access!');
        }

       $advocates=Advocate::all();
        return view('backend.pages.advocates.index', compact('advocates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('advocate.create')) {
            abort(403, 'Unauthorized Access!');
        }

        $advocates=Advocate::all();
        return view('backend.pages.advocates.create', compact('advocates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('advocate.create')) {
            abort(403, 'Unauthorized Access!');
        }





        try {
            $advocate= Advocate::create($request->all());
            return ['status'=>'success','data'=>$advocate,'msg'=>' Advocate has been Created !!'];


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
        if (is_null($this->user) || !$this->user->can('advocate.edit')) {
            abort(403, 'Unauthorized Access!');
        }


        $advocate = Advocate::findOrFail($id);
        return view('backend.pages.advocates.create', compact('advocate'));
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
        if (is_null($this->user) || !$this->user->can('advocate.edit')) {
            abort(403, 'Unauthorized Access!');
        }


        $advocate = Advocate::findOrFail($id);
        try {
            $advocate =$advocate-> update($request->all());
            return ['status'=>'success','data'=>$advocate,'msg'=>' Advocate has been Updated !!'];

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
        if (is_null($this->user) || !$this->user->can('advocate.delete')) {
            abort(403, 'Unauthorized Access!');
        }

        $advocate= Advocate::find($id);

        if(!is_null($advocate)){
            $advocate->delete();
            session()->flash('success', 'Advocate has been Deleted!!');
        }else {
            session()->flash('failed', 'Advocate could not be deleted!!');
        }
        return back();
    }
}
