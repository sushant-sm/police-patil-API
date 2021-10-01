<?php

namespace App\Http\Controllers;

use App\Movementregister;
use Illuminate\Http\Request;
use Validator;
class MovementregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return response()->json(["message" => "No Movement found"]);
        $movement = Movementregister::all();
        if(is_null($movement)){
            return response()->json(["error" => "No Seize register found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $movement], 200);
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
            'subtype' => 'required|string',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'datetime' => 'required',
            'essue' => 'required|boolean', 
            'attendance' => 'required|integer', 
            'description' => 'required', 
            'photo' => 'required',
            'ppid' => 'required',
            'psid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        $movement = Movementregister::create($request->all());

        return response()->json(["message" => "Success", "data" => $movement], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movementregister  $movementregister
     * @return \Illuminate\Http\Response
     */
    public function show(Movementregister $movementregister, $id)
    {
        $movement = Movementregister::find($id);
        if(is_null($movement)){
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $movement], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movementregister  $movementregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Movementregister $movementregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movementregister  $movementregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movementregister $movementregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movementregister  $movementregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movementregister $movementregister)
    {
        //
    }

    public function showbyppid($ppid) 
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if($ppid == $uid)
        {
            $data = Movementregister::orderBy('id','desc')->where('ppid', $ppid)->get();
            if(is_null($data)){
                return response()->json(["error" => "Record Not found"], 404);
            }
            if($data->isEmpty()){
                return response()->json(["error" => "Record Empty"], 404);
            }
            return response()->json(["message" => "Success", "data" => $data], 200);    
        }
        else 
        {
            return response()->json(["error" => "Your Not authorised Person"], 404); 
        }
    }

    public function showbypsid($psid) 
    {
        $data = Movementregister::orderBy('id','desc')->where('psid', $psid)->get();
        if(is_null($data)){
            return response()->json(["error" => "Record Not found"], 404);
        }
        if($data->isEmpty()){
            return response()->json(["error" => "Record Empty"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);    
    }
}
