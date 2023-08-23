<?php

namespace App\Http\Controllers;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
//     //
//     public function user()
// {
//      return view('welcome'); //replace with the view (blade file) you want
// }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8'
        ]);
// dd($request->office_start_time,);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'office_start_time' => $request->office_start_time,
            // 'office_end_time' => $request->office_end_time,
            'office_start_time' => Carbon::parse("2023-07-06 10:00:00"),
            'office_end_time' => Carbon::parse("2023-07-06 18:00:00"),
            'employee_id' => $request->employee_id,

            'designation' => $request->designation,
            'department' => $request->department,
            'status' => $request->status,
            // 'report_for' => $request->report_for,
            'date_joined' => $request->date_joined,
            'date_released' => $request->date_released,
        ]);




        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $request->name,

        ]);

    }



    public function login(Request $request)
    {


        $request->validate([

            'email' => 'required|email|max:250',
            'password' => 'required|min:8'
        ]);
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $user->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data'=>['user'=>$user,
            'token' => $user->createToken('myapptoken')->plainTextToken],



        ]);

    }
    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users,email,'.$request->id,
            'password' => 'required|min:8'
        ]);

        $user=User::findOrFail( $request->id);


        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'success updates',
            'data' => $request->name,
        ]);

    }

//fetch user profiles
    public function profile()
    {
        if(Auth::check())
        {
           $user= auth::user();
            return response()->json([
                'status' => 200,
                'message' => 'Viewing Profile',
                'data' => $user,
            ]);
        }
        return response()->json([
            'status' => 500,
            'message' => 'Failed',
            'data' => '$request->name',
        ]);

    }

//update user profiles
public function updateProfile(Request $request)
{
    $user=auth()->user();
    $users=User::findOrFail( $user->id);
    // dd($users);
       $users->update([

        'name' => $request->name,
        'mobile' => $request->mobile,
        'p_address' => $request->p_address,
        't_address' => $request->t_address,
        'qualification' => $request->qualification,
        'contact_person' => $request->contact_person,
        'contact_person_details' => $request->contact_person_details,
        'gender' => $request->gender,
        'dob' => $request->dob,
        'religion' => $request->religion,



        'extra' => $request->extra,

    ]);


        return response()->json([
            'status' => 200,
            'message' => 'Updating Profile',
            'data'=>['user'=>$users,
            'token' => $users->createToken('myapptoken')->plainTextToken],

        ]);
    // if(Auth::check())
    // {

    // }
    // return response()->json([
    //     'status' => 500,
    //     'message' => 'Failed',
    //     'data' => '$request->name',
    // ]);

}

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }

}
