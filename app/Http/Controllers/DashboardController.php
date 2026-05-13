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
use Illuminate\Support\Facades\Cache;
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

            // Cache dashboard statistics for 5 minutes to improve performance
            $cacheKey = 'dashboard_stats_' . date('Y-m-d-H');
            
            $stats = Cache::remember($cacheKey, 300, function() {
                // Optimize: Use single query with subqueries for better performance
                $currentMonth = date('m');
                $currentYear = date('Y');
                
                return [
                    'advocates' => Advocate::count(),
                    'history' => History::whereMonth('date', $currentMonth)
                                        ->whereYear('date', $currentYear)
                                        ->count(),
                    'project' => Project::count(),
                    'cases' => CaseItem::count(),
                ];
            });

            // Upcoming cases in next 7 days (cached for 15 minutes)
            $nextdaysCacheKey = 'upcoming_cases_' . date('Y-m-d-H');
            $nextdays = Cache::remember($nextdaysCacheKey, 900, function() {
                $from = Carbon::now();
                $to = Carbon::now()->addDays(7)->toDateString();
                return History::whereBetween('next_date', [$from, $to])
                              ->orderBy('next_date', 'asc')
                              ->get();
            });

            $date_m = Carbon::now();
            $month_name = $date_m->format('F');

            return view('backend.pages.dashboard.index', compact(
                'statData',
                'month_name',
                'nextdays'
            ) + $stats);
        }
        //return view('backend.pages.dashboard.index');
    }
}
