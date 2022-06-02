<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class ProjectController extends Controller
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
        if (is_null($this->user) || !$this->user->can('project.view')) {
            abort(403, 'Unauthorized Access!');
        }

        $projects = Project::all();
        return view('backend.pages.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('project.create')) {
            abort(403, 'Unauthorized Access!');
        }

        $projects = Project::all();
        return view('backend.pages.projects.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('project.create')) {
            abort(403, 'Unauthorized Access!');
        }


        try {
            $project= Project::create($request->all());
            return ['status'=>'success','data'=>$project,'msg'=>' Project has been Created !!'];


        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Creating Project !!');
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
        if (is_null($this->user) || !$this->user->can('project.edit')) {
            abort(403, 'Unauthorized Access!');
        }

        $project = Project::findOrFail($id);
        return view('backend.pages.projects.create', compact('project'));
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
        if (is_null($this->user) || !$this->user->can('project.edit')) {
            abort(403, 'Unauthorized Access!');
        }

        $project = Project::findOrFail($id);
        try {
            $project =$project-> update($request->all());
            return ['status'=>'success','data'=>$project,'msg'=>' Project has been Updated !!'];

        }
        catch (Exception $exception) {

            return redirect() ->back()->with('failed','Failed Updating Project!!');
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
        if (is_null($this->user) || !$this->user->can('project.delete')) {
            abort(403, 'Unauthorized Access!');
        }
        $project = Project::find($id);
        if(!is_null($project)){
            $project->delete();
            session()->flash('success', 'Project has been Deleted!!');
        }else {
            session()->flash('failed', 'Project could not be deleted!!');
        }
        return back();
    }
}
