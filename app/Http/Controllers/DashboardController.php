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
                $from = Carbon::today()->addDays(2)->startOfDay();
                $to = $from->copy()->addDays(6)->endOfDay();
                return History::whereBetween('next_date', [$from, $to])
                              ->with(['cases.advocates', 'cases.projects', 'cases.divisions', 'cases.types', 'cases.courts'])
                              ->orderBy('next_date', 'asc')
                              ->get();
            });
            $nextdaysFrom = Carbon::today()->addDays(2);
            $nextdaysTo = $nextdaysFrom->copy()->addDays(6);

            // Group upcoming cases per day for the 7 individual day sections
            $groupedNextdays = $nextdays->groupBy(function ($item) {
                return Carbon::parse($item->next_date)->format('Y-m-d');
            });
            $nextdaysByDay = collect(range(0, 6))->map(function ($i) use ($nextdaysFrom, $groupedNextdays) {
                $date = $nextdaysFrom->copy()->addDays($i);
                return [
                    'date' => $date,
                    'cases' => $groupedNextdays->get($date->format('Y-m-d'), collect()),
                ];
            });

            // Today's cases (cached for 5 minutes)
            $todayCacheKey = 'today_cases_' . date('Y-m-d');
            $todayCases = Cache::remember($todayCacheKey, 300, function() {
                return History::whereDate('next_date', Carbon::today())
                              ->with(['cases.advocates'])
                              ->orderBy('next_date', 'asc')
                              ->get();
            });

            // Tomorrow's cases (cached for 5 minutes)
            $tomorrowCacheKey = 'tomorrow_cases_' . date('Y-m-d');
            $tomorrowCases = Cache::remember($tomorrowCacheKey, 300, function() {
                return History::whereDate('next_date', Carbon::tomorrow())
                              ->with(['cases.advocates'])
                              ->orderBy('next_date', 'asc')
                              ->get();
            });

            $date_m = Carbon::now();
            $month_name = $date_m->format('F');

            return view('backend.pages.dashboard.index', compact(
                'month_name',
                'nextdays',
                'nextdaysFrom',
                'nextdaysTo',
                'nextdaysByDay',
                'todayCases',
                'tomorrowCases'
            ) + $stats);
        }
        //return view('backend.pages.dashboard.index');
    }
}
