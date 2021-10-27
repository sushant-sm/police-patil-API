<?php

namespace App\Http\Controllers;

use App\Fireregister;
use Validator;
use App\Policestation;
use Illuminate\Http\Request;

class FireregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $userRole = $loggedinuser->role;
        $psid = $loggedinuser->psid;
        $psname = Policestation::where('id', $psid)->get('psname');

        if ($userRole == 'admin') {
            $data = Fireregister::get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else if ($userRole == 'ps') {
            $data = Fireregister::where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data, "psname" => $psname], 200);
        } else if ($userRole == 'pp') {
            $data = Fireregister::where('ppid', $uid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["message" => "You are not authorized person.l̥"], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'address' => 'required|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'date' => 'required',
            'time' => 'nullable',
            'reason' => 'nullable|string',
            'loss' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;

        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/fireregister/' . time() . '.' . $extension;
            $file->move('uploads/fireregister', $filename);
            $data['photo'] = $filename;
        }

        $fire = Fireregister::create($data);
        app('App\Http\Controllers\PointsController')->addpoint();
        return response()->json(["message" => "Success", "data" => $fire], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fireregister  $fireregister
     * @return \Illuminate\Http\Response
     */
    public function show(Fireregister $fireregister, $id)
    {
        $fireregister = Fireregister::find($id);
        return response()->json(["message" => "Success", "data" => $fireregister], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fireregister  $fireregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Fireregister $fireregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fireregister  $fireregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fireregister $fireregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fireregister  $fireregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fireregister $fireregister)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Fireregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }

    public function showbypsid($psid)
    {
        $data = Fireregister::orderBy('id', 'desc')->where('psid', $psid)->get();
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
