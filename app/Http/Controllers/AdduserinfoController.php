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
        if (is_null($user)) {
            return response()->json(["error" => "Record Not found"], 201);
        }
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
        return response()->json(["message" => "Success", "data" => $user], 200);
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
        //
        // $user->update($request->all());
        // return response()->json(["message" => "User Updated Succesfully", "data" => $user], 200);

        $data = $request->validate([
            'name' => 'required|string',
            'village' => 'required|string',
            'mobile' => 'nullable|numeric|digits:10',
            'address' => 'nullable|string',
            'joindate' => 'nullable',
            'enddate' => 'nullable',
            'psdistance' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'psid' => 'required',
            'taluka' => 'required',
            'password' => 'required'
        ]);


        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($uid != $data['ppid']) {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }

        $pass = $data['password'];

        $data['password'] = Hash::make($pass);

        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
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

    public function showbyppid($id)
    {
        $data = User::orderBy('id', 'desc')->where('id', $id)->get();
        if (is_null($data)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
