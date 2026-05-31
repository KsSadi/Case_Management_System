<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });

    }

    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('report.month')) {
            abort(403, 'Unauthorized Access!');
        }

        $month = (int) $request->input('month', date('m'));
        $year  = (int) $request->input('year', date('Y'));

        $histories = History::whereMonth('next_date', $month)
            ->whereYear('next_date', $year)
            ->get();

        $month_name = Carbon::createFromDate($year, $month, 1)->format('F');

        return view('backend.pages.reports.months.index', compact('histories', 'month_name', 'month', 'year'));


    }
    public function Previous()
    {
        if (is_null($this->user) || !$this->user->can('report.month')) {
            abort(403, 'Unauthorized Access!');
        }
        $histories=History::whereMonth('created_at', '=',Carbon::now()->subMonth()->month)->get();

        $date = \Carbon\Carbon::now();
        $lastMonth =  $date->subMonth()->format('F');


        return view('backend.pages.reports.months.previous', compact('histories','lastMonth'));


    }




}
