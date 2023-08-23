<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\DailyReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class DailyReportController extends Controller
{
     //
     public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:250',
            'description' => 'required|string',

        ]);


        $user= auth()->user();
        // $dailyReport= User::find($user->id)->dailyReports;
        // $attendanceId=0;

        // $dailyReport= Attendance::where('date', '==',"2023-02-11" )->first();

        $report_for_date =  Carbon::now();
        $daily= DailyReport::create([

            'title'=>$request->title,
            'description' => $request->description,
            'report_for' => $report_for_date,
            'user_id'=> $user->id,
            'extra' => $request->extra,

        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Daily Report recorded successfully!',
            'data' => [
                'daily_reports' => $daily,
            ]

        ]);

    }
    public function update(Request $request){
        $request->validate([
            'id'=>'required',
            'title' => 'required|string|max:250',
            'description' => 'required|string',

        ]);
        $user=auth()->user();
        $report_for_date =  Carbon::now();
        // $dailyReport= Attendance::where('date', '==',"2023-02-11" )->first();
        $dailyReport = User::findOrFail($user->id);

        $dailyReport = $dailyReport->daily_reports()->whereDate('report_for',$report_for_date)->find($request->id);
        // $dailyReport= DailyReport::whereDate('report_for',$report_for_date)->first();
        $dailyReport->update([
            'title'=>$request->title,
            'description' => $request->description,

        ]);
        return response()->json([
            'status' => 200,
            'message' => 'fetching..!',
            'data' => [
                'attendance' => $dailyReport,
            ]

        ]);
    }


    public function indexMonthly(){
        $user=auth()->user();
        $report_for_date_y =  Carbon::now()->year;
        $report_for_date_m =  Carbon::now()->month;



        $dailyReport = User::findOrFail($user->id);

        $monthlyReports = $dailyReport->daily_reports()
        ->whereYear('created_at', $report_for_date_y)
        ->whereMonth('created_at', $report_for_date_m)
        ->get();

        return response()->json([
            'status' => 200,
            'message' => 'fetching..!',
            'data' => [
                'monthly_reports' => $monthlyReports,
            ]

        ]);
    }

    public function indexDaily(){
        $user=auth()->user();

        $report_for_date =  Carbon::now();



        $dailyReport = User::findOrFail($user->id);

        $monthlyReports = $dailyReport->daily_reports()
        ->whereDate('created_at', $report_for_date)
        ->latest()->first();

        return response()->json([
            'status' => 200,
            'message' => 'fetching..!',
            'data' => [
                'daily_reports' => $monthlyReports,
            ]

        ]);
    }

    public function monthlyReport(Request $request){
        $user = auth()->user();

        $report_for_date_y = $request->report_for_date_y ?? carbon::now()->year;
        $report_for_date_m = $request->report_for_date_m ?? carbon::now()->month;
      
    
        $dailyReport = User::findOrFail($user->id);
    
        $monthlyReports = $dailyReport->daily_reports()
            ->whereYear('report_for', $report_for_date_y)
            ->whereMonth('report_for', $report_for_date_m)
            ->get();
    
        return response()->json([
            'status' => 200,
            'message' => 'Fetching this month report!',
            'data' => [
                'monthly_reports' => $monthlyReports,
            ]
        ]);
    }
    
}
