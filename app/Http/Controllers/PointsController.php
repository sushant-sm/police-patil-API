<?php

namespace App\Http\Controllers;

use App\Points;
use DB;
use App\User;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addpoint()
    {
        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $role = $loggedinuser->role;

        if ($role == 'pp') {
            if (Points::where('ppid', $ppid)->exists()) {
                $a = DB::table('points')->where('ppid', $ppid)->increment('points', 1);
            } else {
                $data['ppid'] = $ppid;
                $data['points'] = 1;
                Points::create($data);
            }
        }
    }

    public function index()
    {
        $top = Points::orderBy('points', 'DESC')->limit(10)->get('ppid');
        $psname = $this->getpp($top);
        return response()->json(["message" => "Sucees", "data" => $psname], 200);
    }
    public function getpp($ppid)
    {
        $data = User::whereIn('id', $ppid)->get();
        return $data;
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
     * @param  \App\Points  $points
     * @return \Illuminate\Http\Response
     */
    public function show(Points $points)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Points  $points
     * @return \Illuminate\Http\Response
     */
    public function edit(Points $points)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Points  $points
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Points $points)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Points  $points
     * @return \Illuminate\Http\Response
     */
    public function destroy(Points $points)
    {
        //
    }
}
