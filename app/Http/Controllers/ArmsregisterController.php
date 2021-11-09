<?php

namespace App\Http\Controllers;

use App\Armsregister;
use App\Policestation;
use Validator;
use File;
use App\Points;
use Excel;
use App\Exports\ArmsExport;
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
    public function exportIntoExcel()
    {
        return Excel::download(new ArmsExport, 'armsdata.xlsx');
    }


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
                $my_query = Armsregister::query();
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
                $data = Armsregister::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $psid = $loggedinuser->psid;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $q = $request->query();
            if ($q != NULL) {
                $my_query = Armsregister::query();
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

    public function index1()
    {
        return Armsregister::where('id', 1)->where('type', 'something')->get();
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
