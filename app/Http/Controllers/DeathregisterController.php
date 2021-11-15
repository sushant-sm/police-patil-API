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

        if ($userRole == 'admin') {

            $q = $request->query();
            if ($q != NULL) {
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                $my_query = Deathregister::query();
                if ($fromdate != NULL and $todate != NULL) {
                    if (!empty($q['isknown'])) {
                        $my_query->where('isknown', $q['isknown']);
                    }
                    if (!empty($q['gender'])) {
                        $my_query->where('gender', $q['gender']);
                    }
                    if (!empty($q['psid'])) {
                        $my_query->where('psid', $q['psid']);
                    }
                    if (!empty($q['ppid'])) {
                        $my_query->where('ppid', $q['ppid']);
                    }
                    $my_query->whereBetween('date', [$fromdate, $todate]);
                } else {
                    foreach ($request->query() as $key => $value) {
                        $my_query->where($key, $value);
                    }
                }
                $data = $my_query->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Deathregister::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {
            $psid = $loggedinuser->psid;
            $q = $request->query();
            if ($q != NULL) {
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                $my_query = Deathregister::query();
                if ($fromdate != NULL and $todate != NULL) {
                    if (!empty($q['isknown'])) {
                        $my_query->where('isknown', $q['isknown']);
                    }
                    if (!empty($q['gender'])) {
                        $my_query->where('gender', $q['gender']);
                    }
                    if (!empty($psid)) {
                        $my_query->where('psid', $psid);
                    }
                    if (!empty($q['ppid'])) {
                        $my_query->where('ppid', $q['ppid']);
                    }
                    $my_query->whereBetween('date', [$fromdate, $todate]);
                } else {
                    foreach ($request->query() as $key => $value) {
                        $my_query->where($key, $value);
                    }
                    $my_query->where('psid', $psid);
                }
                $data = $my_query->get();
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
    public function update(Request $request)
    {
        $deathid = $request->deathid;
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $getppid = Deathregister::where('id', $deathid)->pluck('ppid');
        $getppid = trim($getppid, '[]',);
        $getppid = (int)$getppid;

        $getpsid = Deathregister::where('id', $deathid)->pluck('ppid');
        $getpsid = trim($getpsid, '[]',);
        $getpsid = (int)$getpsid;

        if ($getppid == $uid or $getpsid == $uid) {
            $data = $request->validate([
                'isknown' => 'nullable',
                'name' => 'nullable|string',
                'gender' => 'nullable',
                'address' => 'nullable|string',
                'date' => 'nullable',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
                'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
                'foundaddress' => 'nullable',
                'causeofdeath' => 'nullable',
                'age' => 'nullable|numeric',
                'actionTaken' => 'nullable',
            ]);

            if ($request->hasfile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $filename = 'pp.thesupernest.com/uploads/deathregister/' . time() . '.' . $extension;
                $file->move('uploads/deathregister', $filename);
                $data['photo'] = $filename;
            }

            $death = Deathregister::where('id', $deathid)->update($data);
            return response()->json(["message" => "Success", "data" => $death], 200);
        } else {
            return response()->json(["message" => "You are not authorized person."], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deathregister  $deathregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $deathid = $request->deathid;
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $getppid = Deathregister::where('id', $deathid)->pluck('ppid');
        $getppid = trim($getppid, '[]',);
        $getppid = (int)$getppid;

        $getpsid = Deathregister::where('id', $deathid)->pluck('ppid');
        $getpsid = trim($getpsid, '[]',);
        $getpsid = (int)$getpsid;

        if ($getppid == $uid or $getpsid == $uid) {
            $death = Deathregister::find($deathid);
            $death->delete();
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
