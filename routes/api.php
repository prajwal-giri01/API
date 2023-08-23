<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

    Route::post('/store', [UserController::class, 'store'])->name('store');

    Route::post('/login', [UserController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {



        Route::post('/update', [UserController::class, 'update'])->name('update');
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');

        Route::get('/get-profile', [UserController::class, 'profile'])->name('get.profile');
        Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update.profile');


        Route::get('attendance-check', [AttendanceController::class, 'checkIfNewDay'])->name('attendance.checkIfNewDay');
        Route::post('attendance-store-check-in', [AttendanceController::class, 'storeCheckIn'])->name('attendance.storeCheckIn');
        Route::post('attendance-store-check-out', [AttendanceController::class, 'storeCheckOut'])->name('attendance.storeCheckOut');

        Route::get('attendance-get', [AttendanceController::class, 'index'])->name('attendance.get');


        Route::get('daily-report-get-monthly', [DailyReportController::class, 'indexMonthly']);
            Route::post('daily-report-store', [DailyReportController::class, 'store']);
            Route::post('daily-report-update', [DailyReportController::class, 'update']);
            Route::get('daily-report-get-daily', [DailyReportController::class, 'indexDaily']);

        
            Route::get('monthly-report',[DailyReportController::class, 'monthlyReport']);
            Route::get('monthly-attendance',[AttendanceController::class, 'monthlyAttendance']);


    });


