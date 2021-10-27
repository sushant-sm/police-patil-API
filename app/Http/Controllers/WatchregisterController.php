<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Watchregister;
use App\Policestation;

class WatchregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Watchregister::get();
        return response()->json(["message" => "Success", "data" => $data], 200);
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
            'mobile' => 'nullable|numeric|digits:10',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'aadhar' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'address' => 'nullable',
            'tadipar_area' => 'nullable',
            'tadipar_date' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'description' => 'nullable|string',
            'otherphoto' => 'nullable|image|mimes:jpg,png,jpeg,svg',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;

        if ($request->hasfile('aadhar')) {
            $file = $request->file('aadhar');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/watchregister/aadhar/' . time() . '.' . $extension;
            $file->move('uploads/watchregister/aadhar', $filename);
            $data['aadhar'] = $filename;
        }
        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/watchregister/photo/' . time() . '.' . $extension;
            $file->move('uploads/watchregister/photo', $filename);
            $data['photo'] = $filename;
        }
        if ($request->hasfile('otherphoto')) {
            $file = $request->file('otherphoto');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/watchregister/otherphoto/' . time() . '.' . $extension;
            $file->move('uploads/watchregister/otherphoto', $filename);
            $data['otherphoto'] = $filename;
        }

        $watch = Watchregister::create($data);

        return response()->json(["message" => "Success", "data" => $watch], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $armsregister = Watchregister::find($id);
        if (is_null($armsregister)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json($armsregister, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Watchregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
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
            $data = Watchregister::orderBy('id', 'desc')->where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 200);
        }
    }
}
