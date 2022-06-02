<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TypeRequest;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class CaseTypeController extends Controller
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
        if (is_null($this->user) || !$this->user->can('type.view')) {
            abort(403, 'Unauthorized Access!');
        }

        $types = Type::all();
        return view('backend.pages.types.index', compact('types'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('type.create')) {
            abort(403, 'Unauthorized Access!');
        }

        $types = Type::all();

        return view('backend.pages.types.create', compact('types'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeRequest $request)
    {

        if (is_null($this->user) || !$this->user->can('type.create')) {
            abort(403, 'Unauthorized Access!');
        }




        try {
            $type = Type::create($request->all());
            return ['status'=>'success','data'=>$type,'msg'=>' Case Type has been Created !!'];
            return redirect() ->back()->with('success',' Case Type has been Created !!');

        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Creating Case Type !!');
        }
//

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
        $type=Type::findOrFail($id);
        return view('backend.pages.types.create', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TypeRequest $request, $id)
    {

        if (is_null($this->user) || !$this->user->can('type.edit')) {
            abort(403, 'Unauthorized Access!');
        }

        $type=Type::findOrFail($id);
        try {
            $type =$type-> update($request->all());
            return ['status'=>'success','data'=>$type,'msg'=>' Case Type has been been Created !!'];

        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Updating Case Type !!');
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
        if (is_null($this->user) || !$this->user->can('type.delete')) {
            abort(403, 'Unauthorized Access!');
        }
        $type = Type::find($id);

        if(!is_null($type)){
            $type->delete();
            session()->flash('success', 'Case Type has been Deleted!!');
        }else {
            session()->flash('failed', 'Case Type could not be deleted!!');
        }
        return back();
    }
}
