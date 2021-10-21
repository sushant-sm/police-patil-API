<?php

namespace App\Http\Controllers;

use App\Crimeregister;
use App\Policestation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class CrimeregisterController extends Controller
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
            $data = Crimeregister::get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else if ($userRole == 'ps') {
            $data = Crimeregister::where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data, "psname" => $psname], 200);
        } else if ($userRole == 'pp') {
            $data = Crimeregister::where('ppid', $uid)->get();
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
            'registernumber' => 'nullable|string',
            'date' => 'nullable',
            'time' => 'nullable',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;

        $crime = Crimeregister::create($data);

        return response()->json(["message" => "Success", "data" => $crime], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Crimeregister  $crimeregister
     * @return \Illuminate\Http\Response
     */
    public function show(Crimeregister $crimeregister, $id)
    {
        $crimeregister = Crimeregister::find($id);
        if (is_null($crimeregister)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $crimeregister], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Crimeregister  $crimeregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Crimeregister $crimeregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Crimeregister  $crimeregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Crimeregister $crimeregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Crimeregister  $crimeregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crimeregister $crimeregister)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Crimeregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
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
            $data = Crimeregister::orderBy('id', 'desc')->where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }
    public function crimecount()
    {
        $users = Crimeregister::select('id', 'date')
            ->get()
            ->groupBy(function ($date) {

                return Carbon::parse($date->date)->format('y'); // grouping by months
            });

        // return count($users['21']);
        $usermcount = [];
        $userArr = [];

        foreach ($users as $key => $value) {
            $usermcount[(int)$key] = count($value);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (!empty($usermcount[$i])) {
                $userArr[$i] = $usermcount[$i];
            } else {
                $userArr[$i] = 0;
            }
        }
        return response()->json(["message" => "Success", "data" => $userArr], 200);
    }
}
