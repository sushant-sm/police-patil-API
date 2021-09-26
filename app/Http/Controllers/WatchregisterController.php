<?php

namespace App\Http\Controllers;

use App\Watchregister;
use Validator;
use Illuminate\Http\Request;

class WatchregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $watch = Watchregister::get();
        if(is_null($watch)){
            return response()->json(["error" => "No Arms found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $watch], 200);
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
        $rules = [
            'type' => 'required|string',
            'name' => 'required|string',
            'mobile' => 'required|numeric|digits:10',
            'photo' => 'required',
            'aadhar' => 'required',
            'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'description' => 'required|string',
            'otherphoto' => 'required',
            'ppid' => 'required',
            'psid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        $watch = Watchregister::create($request->all());

        return response()->json(["message" => "Success", "data" => $watch], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Watchregister  $watchregister
     * @return \Illuminate\Http\Response
     */
    public function show(Watchregister $watchregister, $id)
    {
        $watchregister = Watchregister::find($id);
        if(is_null($watchregister)){
            return response()->json(["message" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $watchregister], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Watchregister  $watchregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Watchregister $watchregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Watchregister  $watchregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Watchregister $watchregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Watchregister  $watchregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Watchregister $watchregister)
    {
        //
    }

    public function showbyppid($ppid) 
    {
        $data = Watchregister::orderBy('id','desc')->where('ppid', $ppid)->get();
        if(is_null($data)){
            return response()->json(["message" => "Record Not found"], 404);
        }
        if($data->isEmpty()){
            return response()->json(["message" => "Record Empty"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);    
    }

    public function showbypsid($psid) 
    {
        $data = Watchregister::orderBy('id','desc')->where('psid', $psid)->get();
        if(is_null($data)){
            return response()->json(["message" => "Record Not found"], 404);
        }
        if($data->isEmpty()){
            return response()->json(["message" => "Record Empty"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);    
    }
}
