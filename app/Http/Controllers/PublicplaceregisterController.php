<?php

namespace App\Http\Controllers;

use App\Publicplaceregister;
use App\Policestation;
use Validator;
use Illuminate\Http\Request;

class PublicplaceregisterController extends Controller
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
                $data = Publicplaceregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->where('type', $type)->where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else if ($type != NULL or $fromdate != NULL or $todate != NULL or $psid != NULL) {
                $data = Publicplaceregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->orWhere('type', $type)->orWhere('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Publicplaceregister::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $type = $request->type;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $psid = $loggedinuser->psid;

            if ($type != NULL and $fromdate != NULL and $todate != NULL and $psid != NULL) {
                $data = Publicplaceregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->where('type', $type)->where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else if ($type != NULL or $fromdate != NULL or $todate != NULL or $psid != NULL) {
                $data = Publicplaceregister::whereBetween('created_at', [$fromdate . '%', $todate . '%'])->orWhere('type', $type)->orWhere('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Publicplaceregister::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Publicplaceregister::where('ppid', $uid)->get();
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
            'place' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'iscctv' => 'nullable',
            'isessue' => 'nullable',
            'issuereason' => 'nullable',
            'issuecondition' => 'nullable',
            'crimeregistered' => 'nullable',
        ]);


        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;


        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/publicplaceregister/' . time() . '.' . $extension;
            $file->move('uploads/publicplaceregister', $filename);
            $data['photo'] = $filename;
        }

        $publicplace = Publicplaceregister::create($data);
        app('App\Http\Controllers\PointsController')->addpoint();
        return response()->json(["message" => "Success", "data" => $publicplace], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Publicplaceregister  $publicplaceregister
     * @return \Illuminate\Http\Response
     */
    public function show(Publicplaceregister $publicplaceregister, $id)
    {
        $publicplace = Publicplaceregister::find($id);
        if (is_null($publicplace)) {
            return response()->json(["message" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $publicplace], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Publicplaceregister  $publicplaceregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Publicplaceregister $publicplaceregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Publicplaceregister  $publicplaceregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Publicplaceregister $publicplaceregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Publicplaceregister  $publicplaceregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publicplaceregister $publicplaceregister)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Publicplaceregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }

    public function showbypsid($psid)
    {
        $data = Publicplaceregister::orderBy('id', 'desc')->where('psid', $psid)->get();
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
