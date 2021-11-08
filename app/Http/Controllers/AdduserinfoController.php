<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdduserinfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::get();
        // return $user;
        return response()->json(["message" => "Success", "data" => $user], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        // return json_decode($user['dangerzone']);
        // $user['dangerzone'] = json_decode($user['dangerzone']);
        return response()->json(["message" => "Success", "data" => $user], 200);
    }

    public function dangerzone()
    {
        $loggedinuser = auth()->guard('api')->user();
        $id = $loggedinuser->id;
        $user = User::find($id);
        $data = json_decode($user['dangerzone']);
        return response()->json(["message" => "Success", "data" => $data], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        // return $user;
        $data = $request->validate([
            'name' => 'nullable|string',
            'village' => 'nullable|string',
            'mobile' => 'nullable|numeric|digits:10',
            'address' => 'nullable|string',
            'ordernumber' => 'nullable|string',
            'joindate' => 'nullable',
            'enddate' => 'nullable',
            'psdistance' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'taluka' => 'nullable',
            'password' => 'nullable',
            'dangerzone' => 'nullable',
        ]);
        // return $data;

        if ($request->password) {
            // $pass = $request->password;
            $data['password'] = Hash::make($data['password']);
        }

        // if ($request->dangerzone) {
        //     $data['dangerzone'] = json_encode($data['dangerzone'], JSON_UNESCAPED_UNICODE);
        // }

        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/police_patil/' . time() . '.' . $extension;
            $file->move('uploads/police_patil', $filename);
            $data['photo'] = $filename;
        }

        $user = $user->update($data);
        return response()->json(["message" => "User Updated Succesfully", "data" => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function village()
    {
        $data = User::where('role', 'pp')->select('id', 'village')->get();
        return response()->json(["message" => "Success", "data" => $data], 200);
    }

    public function showbyppid($id)
    {
        $data = User::orderBy('id', 'desc')->where('id', $id)->get();
        if (is_null($data)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);
    }

    public function getallpp()
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $role = $loggedinuser->role;
        if ($role == 'ps') {
            $psid = $loggedinuser->psid;
            $data = User::where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else if ($role == 'admin') {
            $data = User::where('role', 'pp')->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["message" => "No Authority"], 200);
        }
    }

    public function getallps()
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $role = $loggedinuser->role;
        if ($role == 'admin') {
            $data = User::where('role', 'ps')->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["message" => "No Authority"], 200);
        }
    }
}
