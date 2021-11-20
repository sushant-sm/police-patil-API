<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start = Carbon::now()->startOfMonth()->format('d-m-Y');
        $end = Carbon::now()->endOfMonth()->format('d-m-Y');
        $date = Carbon::now()->format('m-Y');
        $ppid = $request->id;
        $count = $request->count;
        $name = User::where('id', $ppid)->get('name');
        $name = $name[0]['name'];
        $village = User::where('id', $ppid)->get('village');
        $village = $village[0]['village'];
        $taluka = User::where('id', $ppid)->get('taluka');
        $taluka = $taluka[0]['taluka'];
        $link = "http://127.0.0.1:8000/api/attendance/certificate?start=$start&end=$end&taluka=$taluka&village=$village&name=$name&count=$count";
        $psid = User::where('id', $ppid)->get('psid');
        $psid = $psid[0]['psid'];

        $data = array('date' => $date, 'ppid' => $ppid, 'psid' => $psid, 'link' => $link);
        $data = Attendance::create($data);
        return response()->json(["message" => "Success", "link" => $link, "data" => $data], 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $name = $request->name;
        $start = $request->start;
        $end = $request->end;
        $village = $request->village;
        $taluka = $request->taluka;
        $count = $request->count;
        return view('certificate', compact('name', 'start', 'end', 'village', 'count', 'taluka'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
