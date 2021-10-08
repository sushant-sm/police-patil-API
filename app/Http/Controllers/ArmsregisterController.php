<?php

namespace App\Http\Controllers;

use App\Armsregister;
use Validator;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArmsregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arms = Armsregister::get();
        if (is_null($arms)) {
            return response()->json(["error" => "No Arms found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $arms], 200);
    }

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
            'aadhar' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'address' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'licencenumber' => 'nullable',
            'validity' => 'nullable',
            'licencephoto' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'ppid' => 'required',
            'psid' => 'required'
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($uid != $data['ppid']) {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }


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
        if (is_null($armsregister)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
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
            if (is_null($data)) {
                return response()->json(["error" => "Record Not found"], 404);
            }
            if ($data->isEmpty()) {
                return response()->json(["error" => "Record Empty"], 404);
            }
            // $dataa = $data['aadhar'];
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
            if (is_null($data)) {
                return response()->json(["error" => "Record Not found"], 404);
            }
            if ($data->isEmpty()) {
                return response()->json(["error" => "Record Empty"], 404);
            }
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 200);
        }
    }
}
