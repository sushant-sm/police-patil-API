<?php

namespace App\Http\Controllers;

use App\Disasterhelper;
use Illuminate\Http\Request;

class DisasterhelperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $disaster = Disasterhelper::get();
        return response()->json(["message" => "Success", "data" => $disaster], 200);
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
            'ppid' => 'required',
            'psid' => 'required'
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($uid != $data['ppid']) {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }

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
