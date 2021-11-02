<?php

namespace App\Http\Controllers;

use App\Illegalworkregister;
use Validator;
use App\Policestation;
use Illuminate\Http\Request;

class IllegalworkregisterController extends Controller
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

            if ($type != NULL and $fromdate != NULL and $todate != NULL and $psid != NULL) {
                $data = Illegalworkregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->where('type', $type)->where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else if ($type != NULL or $fromdate != NULL or $todate != NULL or $psid != NULL) {
                $data = Illegalworkregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->orWhere('type', $type)->orWhere('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Illegalworkregister::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $type = $request->type;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $psid = $loggedinuser->psid;

            if ($type != NULL and $fromdate != NULL and $todate != NULL and $psid != NULL) {
                $data = Illegalworkregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->where('type', $type)->where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else if ($type != NULL or $fromdate != NULL or $todate != NULL or $psid != NULL) {
                $data = Illegalworkregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->orWhere('type', $type)->orWhere('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Illegalworkregister::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Illegalworkregister::where('ppid', $uid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["message" => "You are not authorized person.l̥"], 200);
        }
    }


    public function latest()
    {
        $data = Illegalworkregister::latest()->take(10)->get();
        return response()->json(["message" => "Sucees", "data" => $data], 200);
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
            'name' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'vehicle_no' => 'nullable',
            'address' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;


        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/illegalworkregister/' . time() . '.' . $extension;
            $file->move('uploads/illegalworkregister', $filename);
            $data['photo'] = $filename;
        }
        $illegalwork = Illegalworkregister::create($data);
        app('App\Http\Controllers\PointsController')->addpoint();
        return response()->json(["message" => "Success", "data" => $illegalwork], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Illegalworkregister  $illegalworkregister
     * @return \Illuminate\Http\Response
     */
    public function show(Illegalworkregister $illegalworkregister, $id)
    {
        $illegelworkregister = Illegalworkregister::find($id);
        if (is_null($illegelworkregister)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $illegalworkregister], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Illegalworkregister  $illegalworkregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Illegalworkregister $illegalworkregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Illegalworkregister  $illegalworkregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Illegalworkregister $illegalworkregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Illegalworkregister  $illegalworkregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Illegalworkregister $illegalworkregister)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Illegalworkregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }

    public function showbypsid($psid)
    {
        $data = Illegalworkregister::orderBy('id', 'desc')->where('psid', $psid)->get();
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
