<?php

namespace App\Http\Controllers;

use App\Seizeregister;
use Illuminate\Http\Request;
use Validator;

class SeizeregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seize = Seizeregister::get();
        if (is_null($seize)) {
            return response()->json(["error" => "No Seize register found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $seize], 200);
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
            'address' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'date' => 'required|date',
            'description' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'ppid' => 'required',
            'psid' => 'required',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($uid != $data['ppid']) {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }


        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/seizeregister', $filename);
            $data['photo'] = $filename;
        }

        $seize = Seizeregister::create($data);

        return response()->json(["message" => "Success", "data" => $seize], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seizeregister  $seizeregister
     * @return \Illuminate\Http\Response
     */
    public function show(Seizeregister $seizeregister, $id)
    {
        $seizegister = Seizeregister::find($id);
        if (is_null($seizegister)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $seizegister], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Seizeregister  $seizeregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Seizeregister $seizeregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seizeregister  $seizeregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seizeregister $seizeregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seizeregister  $seizeregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seizeregister $seizeregister)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Seizeregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
            if (is_null($data)) {
                return response()->json(["error" => "Record Not found"], 404);
            }
            if ($data->isEmpty()) {
                return response()->json(["error" => "Record Empty"], 404);
            }
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }

    public function showbypsid($psid)
    {
        $data = Seizeregister::orderBy('id', 'desc')->where('psid', $psid)->get();
        if (is_null($data)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        if ($data->isEmpty()) {
            return response()->json(["error" => "Record Empty"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
