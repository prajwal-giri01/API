<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function checkIfNewDay(){

        $user = auth::user();


        // $report_for_date =  Carbon::today()->->addDay(1);
        $report_for_date =  Carbon::today();


        $attendance1 = User::findOrFail($user->id);

        $attendance = $attendance1->attendences()
            ->whereDate('check_in_date', $report_for_date)->exists();
            $attendanceData = $attendance1->attendences()
            ->whereDate('check_in_date', $report_for_date)->first();
            // dd( $attendance);
                // dd( $attendance);
            $text= $attendance?'In':'Out';
                        return response()->json([
                            'status' => 200,
                            'message' =>'You are Checked '. $text,
                            'data' => [
                                'checked_in' => $attendance,
                                'data'=>$attendanceData,
                            ]

                        ]);

                }
    //
    public function storeCheckIn(Request $request)
    {
        $user = auth::user();

        $validatedData = $request->validate([
            // 'user_id' => 'required|numeric',
            'date' => 'required|date',
            'check_in_date' => 'required|date',
            'check_out_date' => 'nullable',
        ]);


        $attendance = new Attendance;
        $attendance->user_id = $user->id;
        $attendance->date = $validatedData['date'];
        $attendance->check_in_date = Carbon::now();
        $attendance->check_out_date = $validatedData['check_out_date'] ?? null;
        $attendance->office_start_time = $user->office_start_time;
        $attendance->office_end_time = $user->office_end_time;
        $attendance->save();

        return response()->json([
            'status' => 200,
            'message' => 'You are Checked In',
            'data' => [
                'checked_in' => $attendance->id,
                'checked_in_date' => $attendance
            ]

        ]);
    }



    public function storeCheckOut(Request $request)
    {

        $user = auth::user();
        $request->validate([
            'check_out_date' => 'required|date',

        ]);

        $report_for_date =  Carbon::now();

        $attendance1 = User::findOrFail($user->id);

        $attendance = $attendance1->attendences()
            ->whereDate('created_at', $report_for_date)
            ->first();

        $check_in_date_ob = new \Carbon\Carbon($attendance->check_in_date);

        $check_out_date_ob =  Carbon::now();
        $office_start_time_ob = new \Carbon\Carbon($user->office_start_time);
        $office_end_time_ob = new \Carbon\Carbon($user->office_end_time);

        // dd( ($check_out_date_ob->diffInMinutes($check_in_date_ob,)));

        if ($attendance != null) {
            $attendance->update([
                // 'check_in_date' => $attendance->check_in_date,
                'check_out_date' => $check_out_date_ob,
                'work_duration' => $check_out_date_ob->diffInMinutes($check_in_date_ob,),
                'late_early_by' => ($office_end_time_ob->diffInMinutes($office_start_time_ob)) - ($check_out_date_ob->diffInMinutes($check_in_date_ob,)),

                'office_start_time' => $user->office_start_time,
                'office_end_time' => $user->office_end_time,
                'user_id' => $user->id,
                'extra' => $request->extra,

            ]);

            return response()->json([
                'status' => 200,
                'message' => 'You are Checked Out',
                'data' => [
                    'checked_out' => 0,
                    'checked_out_date' => $attendance,

                ]

            ]);
        }
        return response()->json([
            'status' => 500,
            'message' => 'Record Not Found',
            'data' => []

        ]);


        //         if (Carbon::parse($attendance->date)->isToday()) {}

    }

    public function index()
    {
        $user = auth()->user();
        $report_for_date_y =  Carbon::now()->year;
        $report_for_date_m =  Carbon::now()->month;

        $attendence = User::findOrFail($user->id);


        $monthly_attendance = $attendence->attendences()
            ->whereYear('created_at', $report_for_date_y)
            ->whereMonth('created_at', $report_for_date_m)
            ->count();
        $total_attendance = $attendence->attendences()
            ->whereYear('created_at', $report_for_date_y)
            ->whereMonth('created_at', $report_for_date_m)
            ->count();
        return response()->json([
            'status' => 200,
            'message' => 'fetching..!',
            'data' => [
                'monthly_attendance' => $monthly_attendance,
                'monthly_attendance' => $total_attendance,
            ]

        ]);
    }

    public function monthlyAttendance(Request $request){
        $user = auth()->user();

        $report_for_date_y = $request->report_for_date_y ?? carbon::now()->year;
        $report_for_date_m = $request->report_for_date_m ?? carbon::now()->month;

        $attendance = User::findOrFail($user->id);
        
        $monthly_attendance = $attendance->attendences()
            ->whereYear('created_at', $report_for_date_y)
            ->whereMonth('created_at', $report_for_date_m)->get();
            return response()->json([
                'status' => 200,
                'message' => 'fetching monthly attendance',
                'data' => [
                    'monthly_attendance' => $monthly_attendance,
            
                ]
    
            ]);

    }
}
