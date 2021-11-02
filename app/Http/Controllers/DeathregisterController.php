<?php

namespace App\Http\Controllers;

use App\Deathregister;
use App\Policestation;
use Illuminate\Http\Request;

class DeathregisterController extends Controller
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

            if ($fromdate != NULL and $todate != NULL and $psid != NULL) {
                $data = Deathregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->where('type', $type)->where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else if ($fromdate != NULL or $todate != NULL or $psid != NULL) {
                $data = Deathregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->orWhere('type', $type)->orWhere('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Deathregister::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $type = $request->type;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $psid = $loggedinuser->psid;

            if ($fromdate != NULL and $todate != NULL and $psid != NULL) {
                $data = Deathregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->where('type', $type)->where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else if ($fromdate != NULL or $todate != NULL or $psid != NULL) {
                $data = Deathregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->orWhere('type', $type)->orWhere('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Deathregister::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Deathregister::where('ppid', $uid)->get();
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
            'isknown' => 'required',
            'name' => 'nullable|string',
            'gender' => 'required',
            'address' => 'nullable|string',
            'date' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'foundaddress' => 'nullable',
            'causeofdeath' => 'nullable',
            'age' => 'nullable|numeric',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;


        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/deathregister/' . time() . '.' . $extension;
            $file->move('uploads/deathregister', $filename);
            $data['photo'] = $filename;
        }

        $death = Deathregister::create($data);
        app('App\Http\Controllers\PointsController')->addpoint();
        return response()->json(["message" => "Success", "data" => $death], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deathregister  $deathregister
     * @return \Illuminate\Http\Response
     */
    public function show(Deathregister $deathregister, $id)
    {
        $death = Deathregister::find($id);
        if (is_null($death)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $death], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deathregister  $deathregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Deathregister $deathregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deathregister  $deathregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deathregister $deathregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deathregister  $deathregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deathregister $deathregister)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Deathregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
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
            $data = Deathregister::orderBy('id', 'desc')->where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }
}
