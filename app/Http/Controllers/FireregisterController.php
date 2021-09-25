<?php

namespace App\Http\Controllers;

use App\Fireregister;
use Validator;
use Illuminate\Http\Request;

class FireregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fire = Fireregister::get();
        if(is_null($fire)){
            return response()->json(["message" => "No Arms found"], 404);
        }
        return response()->json($fire, 200);
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
            'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'date' => 'required',
            'time' => 'required',
            'reason' => 'required|string',
            'loss' => 'required',
            'photo' => 'required',
            'ppid' => 'required',
            'psid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        $fire = Fireregister::create($request->all());

        return response()->json($fire, 201);
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
        if(is_null($fireregister)){
            return response()->json(["message" => "Record Not found"], 404);
        }
        return response()->json($fireregister, 200);
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
    public function update(Request $request, Fireregister $fireregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fireregister  $fireregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fireregister $fireregister)
    {
        //
    }

    public function showbyppid($ppid) 
    {
        $data = Fireregister::orderBy('id','desc')->where('ppid', $ppid)->get();
        if(is_null($data)){
            return response()->json(["message" => "Record Not found"], 404);
        }
        if($data->isEmpty()){
            return response()->json(["message" => "Record Empty"], 404);
        }
        return response()->json($data, 200);    
    }

    public function showbypsid($psid) 
    {
        $data = Fireregister::orderBy('id','desc')->where('psid', $psid)->get();
        if(is_null($data)){
            return response()->json(["message" => "Record Not found"], 404);
        }
        if($data->isEmpty()){
            return response()->json(["message" => "Record Empty"], 404);
        }
        return response()->json($data, 200);    
    }
}
