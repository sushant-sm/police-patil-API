<?php

namespace App\Http\Controllers;

use App\Armsregister;
use App\Policestation;
use Validator;
use File;
use App\Points;
use App\Http\Controllers\PointsController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;

class ArmsregisterController extends Controller
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
            // $date = \Carbon\Carbon::parse($fromdate);
            // $fromdate = $date->toDateString();
            // $fromdate = date("Y-m-d", strtotime($fromdate));

            $todate = $request->todate;
            // $date = \Carbon\Carbon::parse($todate);
            // $todate = $date->toDateString();
            // $todate = date("Y-m-d", strtotime($todate));

            $psid = $request->psid;
            // $data = [$fromdate, $todate, $psid, $type];
            // return $data;

            if ($type != NULL and $fromdate != NULL and $todate != NULL and $psid != NULL) {
                $data = Armsregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->where('type', $type)->where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else if ($type != NULL or $fromdate != NULL or $todate != NULL or $psid != NULL) {
                $data = Armsregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->orWhere('type', $type)->orWhere('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Armsregister::get();
                // $psid = Armsregister::select('psid')->get();
                // $psname = $this->getpolicename($psid);
                // $data = (['info' => $info, 'psname' => $psname]);
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $type = $request->type;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $psid = $loggedinuser->psid;

            // $data = [$fromdate, $todate, $psid, $type];
            // return $data;


            if ($type != NULL and $fromdate != NULL and $todate != NULL and $psid != NULL) {
                $data = Armsregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->where('type', $type)->where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else if ($type != NULL or $fromdate != NULL or $todate != NULL or $psid != NULL) {
                $data = Armsregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->orWhere('type', $type)->orWhere('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Armsregister::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Armsregister::where('ppid', $uid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["message" => "You are not authorized person.l̥"], 200);
        }
        // $psid = Armsregister::select('psid')->distinct()->get();
        // $psid = $psid;

        // $psname = $this->getpolicename($psid);


        // return response()->json(["message" => "Success", "data" => $arms, "psname" => $psname], 200);
    }

    public function getpolicename($psid)
    {
        $data = Policestation::whereIn('id', $psid)->get(['id', 'psname']);
        return $data;
    }

    // public function showbytype()
    // {
    //     $date = \Carbon\Carbon::parse($date);
    //     $d = $date->toDateString();
    //     $arms = Armsregister::where('created_at', 'like', $d . '%')->where('type', 'like', 'something')->get();
    //     return $arms;
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return responce()->json(["messsage" => "armsrgister"], 200);

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
            'name' => 'required|string',
            'mobile' => 'nullable|numeric|digits:10',
            'aadhar' => 'nullable|image|mimes:jpg,png,jpeg,svg,pdf',
            'address' => 'nullable',
            'uid' => 'nullable',
            'weapon_condition' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'licencenumber' => 'nullable',
            'validity' => 'nullable',
            'licencephoto' => 'nullable|image|mimes:jpg,png,jpeg,svg',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;


        if ($request->hasfile('aadhar')) {
            $file = $request->file('aadhar');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/armsregister/aadhar/' . time() . '.' . $extension;
            $file->move('uploads/armsregister/aadhar', $filename);
            $data['aadhar'] = $filename;
        }
        if ($request->hasfile('licencephoto')) {
            $file = $request->file('licencephoto');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/armsregister/licencephoto/' . time() . '.' . $extension;
            $file->move('uploads/armsregister/LicencePhoto', $filename);
            $data['licencephoto'] = $filename;
        }

        $arms = Armsregister::create($data);

        app('App\Http\Controllers\PointsController')->addpoint();

        return response()->json(["message" => "Success", "data" => $arms], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Armsregister  $armsregister
     * @return \Illuminate\Http\Response
     */
    public function show(Armsregister $armsregister, $id)
    {
        $armsregister = Armsregister::find($id);
        return response()->json($armsregister, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Armsregister  $armsregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Armsregister $armsregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Armsregister  $armsregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Armsregister $armsregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Armsregister  $armsregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Armsregister $armsregister)
    {
        //
    }



    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Armsregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
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
            $data = Armsregister::orderBy('id', 'desc')->where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 200);
        }
    }
}
