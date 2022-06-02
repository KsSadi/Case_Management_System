<?php

namespace App\Http\Controllers;

use App\Models\Advocate;
use App\Models\CaseItem;
use App\Models\History;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
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
        //print_r(gettype($this->user));
        if($this->user == null){
            return view("backend.auth.login");
        }
        else{
            $statData = [];

            $advocates =Advocate:: count();
            $history =History::whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))->get()->count();

           $from = Carbon::now();
           $to = Carbon::now()->subDays(-7)->toDateString();
           $nextdays = History::whereBetween('next_date', [$from, $to])->get();



            $project = Project::count();

             $cases = CaseItem:: count();

            $date_m = Carbon::now();

            $month_name = $date_m->format('F');

            return view('backend.pages.dashboard.index', compact('statData','advocates','cases','history','project','month_name','nextdays'));




        }
        //return view('backend.pages.dashboard.index');
    }
}
