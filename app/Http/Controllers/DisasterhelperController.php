<?php

namespace App\Http\Controllers;

use App\Disasterhelper;
use App\Policestation;
use Illuminate\Http\Request;

class DisasterhelperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $userRole = $loggedinuser->role;
        $psid = $loggedinuser->psid;
        $psname = Policestation::where('id', $psid)->get('psname');

        if ($userRole == 'admin') {
            $data = Disasterhelper::get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else if ($userRole == 'ps') {
            $data = Disasterhelper::where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else if ($userRole == 'pp') {
            $data = Disasterhelper::where('ppid', $uid)->get();
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
            'name' => 'required|string',
            'skill' => 'required|string',
            'mobile' => 'required|numeric|digits:10',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;

        $disaster = Disasterhelper::create($data);

        return response()->json(["message" => "Success", "data" => $disaster], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Disasterhelper  $disasterhelper
     * @return \Illuminate\Http\Response
     */
    public function show(Disasterhelper $disasterhelper)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Disasterhelper  $disasterhelper
     * @return \Illuminate\Http\Response
     */
    public function edit(Disasterhelper $disasterhelper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Disasterhelper  $disasterhelper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disasterhelper $disasterhelper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Disasterhelper  $disasterhelper
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disasterhelper $disasterhelper)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Disasterhelper::orderBy('id', 'desc')->where('ppid', $ppid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }

    public function showbypsid($psid)
    {

        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($psid == $uid) {
            $data = Disasterhelper::orderBy('id', 'desc')->where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }
}
