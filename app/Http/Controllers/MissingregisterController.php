<?php

namespace App\Http\Controllers;

use App\Missingregister;
use Validator;
use Illuminate\Http\Request;

class MissingregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $missing = Missingregister::get();
        if(is_null($missing)){
            return response()->json(["error" => "No Arms found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $missing], 200);
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
            'name' => 'required|string',
            'age' => 'required|numeric',
            'gender' => 'required',
            'address' => 'required|string',
            'missingdate' => 'required',
            'ppid' => 'required',
            'psid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        $missing = Missingregister::create($request->all());

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
        if(is_null($missingregister)){
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
    public function update(Request $request, Missingregister $missingregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Missingregister  $missingregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Missingregister $missingregister)
    {
        //
    }
    public function showbyppid($ppid) 
    {
        $data = Missingregister::orderBy('id','desc')->where('ppid', $ppid)->get();
        if(is_null($data)){
            return response()->json(["error" => "Record Not found"], 404);
        }
        if($data->isEmpty()){
            return response()->json(["error" => "Record Empty"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);    
    }

    public function showbypsid($psid) 
    {
        $data = Missingregister::orderBy('id','desc')->where('psid', $psid)->get();
        if(is_null($data)){
            return response()->json(["error" => "Record Not found"], 404);
        }
        if($data->isEmpty()){
            return response()->json(["error" => "Record Empty"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);    
    }
}
