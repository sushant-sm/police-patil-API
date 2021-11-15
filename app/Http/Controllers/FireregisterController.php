<?php

namespace App\Http\Controllers;

use App\Fireregister;
use Validator;
use App\Policestation;
use Illuminate\Http\Request;

class FireregisterController extends Controller
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
                $my_query = Fireregister::query();
                if ($fromdate != NULL and $todate != NULL) {
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
                $data = Fireregister::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $psid = $loggedinuser->psid;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $q = $request->query();
            if ($q != NULL) {
                $my_query = Fireregister::query();
                if ($fromdate != NULL and $todate != NULL) {
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
                $data = Fireregister::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Fireregister::where('ppid', $uid)->get();
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
        // return "sdf";
        $data = $request->validate([
            'address' => 'required|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'date' => 'nullable',
            'time' => 'nullable',
            'reason' => 'nullable|string',
            'loss' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'actionTaken' => 'nullable',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;

        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/fireregister/' . time() . '.' . $extension;
            $file->move('uploads/fireregister', $filename);
            $data['photo'] = $filename;
        }

        $fire = Fireregister::create($data);
        app('App\Http\Controllers\PointsController')->addpoint();
        return response()->json(["message" => "Success", "data" => $fire], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fireregister  $fireregister
     * @return \Illuminate\Http\Response
     */
    public function show(Fireregister $fireregister, $id)
    {
        $fireregister = Fireregister::find($id);
        return response()->json(["message" => "Success", "data" => $fireregister], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fireregister  $fireregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Fireregister $fireregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fireregister  $fireregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $fireid = $request->fireid;
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $getppid = Fireregister::where('id', $fireid)->pluck('ppid');
        $getppid = trim($getppid, '[]',);
        $getppid = (int)$getppid;

        $getpsid = Fireregister::where('id', $fireid)->pluck('ppid');
        $getpsid = trim($getpsid, '[]',);
        $getpsid = (int)$getpsid;

        if ($getppid == $uid or $getpsid == $uid) {
            $data = $request->validate([
                'address' => 'nullable|string',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
                'date' => 'nullable',
                'time' => 'nullable',
                'reason' => 'nullable|string',
                'loss' => 'nullable',
                'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
                'actionTaken' => 'nullable',
            ]);

            if ($request->hasfile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $filename = 'pp.thesupernest.com/uploads/fireregister/' . time() . '.' . $extension;
                $file->move('uploads/fireregister', $filename);
                $data['photo'] = $filename;
            }

            $fire = Fireregister::where('id', $fireid)->update($data);
            return response()->json(["message" => "Success", "data" => $fire], 200);
        } else {
            return response()->json(["message" => "You are not authorized person."], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fireregister  $fireregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $fireid = $request->fireid;
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $getppid = Fireregister::where('id', $fireid)->pluck('ppid');
        $getppid = trim($getppid, '[]',);
        $getppid = (int)$getppid;

        $getpsid = Fireregister::where('id', $fireid)->pluck('ppid');
        $getpsid = trim($getpsid, '[]',);
        $getpsid = (int)$getpsid;

        if ($getppid == $uid or $getpsid == $uid) {
            $fire = Fireregister::find($fireid);
            $fire->delete();
            return response()->json(["message" => "Success"], 200);
        } else {
            return response()->json(["message" => "You are not authorized person."], 404);
        }
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Fireregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }

    public function showbypsid($psid)
    {
        $data = Fireregister::orderBy('id', 'desc')->where('psid', $psid)->get();
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
