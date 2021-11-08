<?php

namespace App\Http\Controllers;

use App\Disaster;
use App\Policestation;
use Illuminate\Http\Request;

class DisasterController extends Controller
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

            $q = $request->query();
            if ($q != NULL) {
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                $my_query = Disaster::query();
                if ($fromdate != NULL and $todate != NULL) {
                    if (!empty($q['type'])) {
                        $my_query->where('type', $q['type']);
                    }
                    if (!empty($q['psid'])) {
                        $my_query->where('psid', $q['psid']);
                    }
                    if (!empty($q['ppid'])) {
                        $my_query->where('ppid', $q['ppid']);
                    }
                    $my_query->whereBetween('created_at', [$fromdate, $todate]);
                } else {
                    foreach ($request->query() as $key => $value) {
                        $my_query->where($key, $value);
                    }
                }
                $data = $my_query->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Disaster::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $psid = $loggedinuser->psid;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $q = $request->query();
            if ($q != NULL) {
                $my_query = Disaster::query();
                if ($fromdate != NULL and $todate != NULL) {
                    if (!empty($q['type'])) {
                        $my_query->where('type', $q['type']);
                    }
                    if (!empty($psid)) {
                        $my_query->where('psid', $psid);
                    }
                    if (!empty($q['ppid'])) {
                        $my_query->where('ppid', $q['ppid']);
                    }
                    $my_query->whereBetween('created_at', [$fromdate, $todate]);
                } else {
                    foreach ($request->query() as $key => $value) {
                        $my_query->where($key, $value);
                    }
                    $my_query->where('psid', $psid);
                }
                $data = $my_query->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Disaster::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Disaster::where('ppid', $uid)->get();
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
            'type' => 'required|string',
            'subtype' => 'required|string',
            'date' => 'required',
            'casuality' => 'nullable',
            'level' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;

        $disaster = Disaster::create($data);
        app('App\Http\Controllers\PointsController')->addpoint();
        return response()->json(["message" => "Success", "data" => $disaster], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Disaster  $disaster
     * @return \Illuminate\Http\Response
     */
    public function show(Disaster $disaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Disaster  $disaster
     * @return \Illuminate\Http\Response
     */
    public function edit(Disaster $disaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Disaster  $disaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disaster $disaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Disaster  $disaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disaster $disaster)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Disaster::orderBy('id', 'desc')->where('ppid', $ppid)->get();
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
            $data = Disaster::orderBy('id', 'desc')->where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 200);
        }
    }
}
