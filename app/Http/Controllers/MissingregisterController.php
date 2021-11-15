<?php

namespace App\Http\Controllers;

use App\Missingregister;
use Validator;
use App\Policestation;
use Illuminate\Http\Request;

class MissingregisterController extends Controller
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
                $my_query = Missingregister::query();
                if ($fromdate != NULL and $todate != NULL) {
                    if (!empty($q['gender'])) {
                        $my_query->where('gender', $q['gender']);
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
                $data = Missingregister::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $psid = $loggedinuser->psid;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $q = $request->query();
            if ($q != NULL) {
                $my_query = Missingregister::query();
                if ($fromdate != NULL and $todate != NULL) {
                    if (!empty($q['gender'])) {
                        $my_query->where('gender', $q['gender']);
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
                $data = Missingregister::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Missingregister::where('ppid', $uid)->get();
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
            'isadult' => 'nullable',
            'name' => 'required|string',
            'age' => 'nullable|numeric',
            'gender' => 'required',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'address' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'missingdate' => 'required',
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
            $filename = 'pp.thesupernest.com/uploads/missingregister/photo/' . time() . '.' . $extension;
            $file->move('uploads/missingregister/photo', $filename);
            $data['photo'] = $filename;
        }

        $missing = Missingregister::create($data);
        app('App\Http\Controllers\PointsController')->addpoint();
        return response()->json(["message" => "Success", "data" => $missing], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Missingregister  $missingregister
     * @return \Illuminate\Http\Response
     */
    public function show(Missingregister $missingregister, $id)
    {
        $missingregister = Missingregister::find($id);
        if (is_null($missingregister)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $missingregister], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Missingregister  $missingregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Missingregister $missingregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Missingregister  $missingregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $missingid = $request->missingid;
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $getppid = Missingregister::where('id', $missingid)->pluck('ppid');
        $getppid = trim($getppid, '[]',);
        $getppid = (int)$getppid;

        $getpsid = Missingregister::where('id', $missingid)->pluck('ppid');
        $getpsid = trim($getpsid, '[]',);
        $getpsid = (int)$getpsid;

        if ($getppid == $uid or $getpsid == $uid) {
            $data = $request->validate([
                'isadult' => 'nullable',
                'name' => 'nullable|string',
                'age' => 'nullable|numeric',
                'gender' => 'nullable',
                'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
                'address' => 'nullable',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
                'missingdate' => 'nullable',
                'actionTaken' => 'nullable',
            ]);

            if ($request->hasfile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $filename = 'pp.thesupernest.com/uploads/missingregister/photo/' . time() . '.' . $extension;
                $file->move('uploads/missingregister/photo', $filename);
                $data['photo'] = $filename;
            }

            $missing = Missingregister::where('id', $missingid)->update($data);
            return response()->json(["message" => "Success", "data" => $missing], 200);
        } else {
            return response()->json(["message" => "You are not authorized person."], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Missingregister  $missingregister
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        $missingid = $request->missingid;
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $getppid = Missingregister::where('id', $missingid)->pluck('ppid');
        $getppid = trim($getppid, '[]',);
        $getppid = (int)$getppid;

        $getpsid = Missingregister::where('id', $missingid)->pluck('ppid');
        $getpsid = trim($getpsid, '[]',);
        $getpsid = (int)$getpsid;

        if ($getppid == $uid or $getpsid == $uid) {
            $missing = Missingregister::find($missingid);
            $missing->delete();
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
            $data = Missingregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }

    public function showbypsid($psid)
    {
        $data = Missingregister::orderBy('id', 'desc')->where('psid', $psid)->get();
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
