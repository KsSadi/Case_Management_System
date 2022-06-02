<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CaseItem;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

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
        $histories=History::all();
        return view('backend.pages.histories.index', compact('histories'));

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
        $histories=History::all();
        $cases=CaseItem::all();
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
        try{
            $history=History::create($request->all());
            return ['status'=>'success','data'=>$history,'msg'=>' Case History has been Created !!'];


        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Creating Case History !!');
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

        $history=History::findOrFail($id);
        try {
            $history =$history-> update($history->all());
            return ['status'=>'success','data'=>$history,'msg'=>' Case History has been Updated !!'];

        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Updating Case History!!');
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
