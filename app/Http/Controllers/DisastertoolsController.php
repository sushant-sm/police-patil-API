<?php

namespace App\Http\Controllers;

use App\Disastertools;
use App\Policestation;
use Illuminate\Http\Request;

class DisastertoolsController extends Controller
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

            $type = $request->type;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $psid = $request->psid;

            if ($type != NULL and $fromdate != NULL and $todate != NULL and $psid != NULL) {
                $data = Disastertools::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->where('type', $type)->where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else if ($type != NULL or $fromdate != NULL or $todate != NULL or $psid != NULL) {
                $data = Disastertools::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->orWhere('type', $type)->orWhere('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Disastertools::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $type = $request->type;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $psid = $loggedinuser->psid;

            if ($type != NULL and $fromdate != NULL and $todate != NULL and $psid != NULL) {
                $data = Disastertools::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->where('type', $type)->where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else if ($type != NULL or $fromdate != NULL or $todate != NULL or $psid != NULL) {
                $data = Disastertools::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->orWhere('type', $type)->orWhere('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Disastertools::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Disastertools::where('ppid', $uid)->get();
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
            'quantity' => 'nullable|string',
            'type' => 'nullable',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;

        $disaster = Disastertools::create($data);

        return response()->json(["message" => "Success", "data" => $disaster], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Disastertools  $disastertools
     * @return \Illuminate\Http\Response
     */
    public function show(Disastertools $disastertools)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Disastertools  $disastertools
     * @return \Illuminate\Http\Response
     */
    public function edit(Disastertools $disastertools)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Disastertools  $disastertools
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disastertools $disastertools)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Disastertools  $disastertools
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disastertools $disastertools)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Disastertools::orderBy('id', 'desc')->where('ppid', $ppid)->get();
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
            $data = Disastertools::orderBy('id', 'desc')->where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }
}
