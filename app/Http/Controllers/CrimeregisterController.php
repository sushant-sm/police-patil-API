<?php

namespace App\Http\Controllers;

use App\Crimeregister;
use Illuminate\Http\Request;
use Validator;
class CrimeregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crime = Crimeregister::get();
        if(is_null($crime)){
            return response()->json(["message" => "No Arms found"], 404);
        }
        return response()->json($crime, 200);
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
            'registernumber' => 'required|string',
            'date' => 'required',
            'time' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        $crime = Crimeregister::create($request->all());

        return response()->json($crime, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Crimeregister  $crimeregister
     * @return \Illuminate\Http\Response
     */
    public function show(Crimeregister $crimeregister, $id)
    {
        $crimeregister = Crimeregister::find($id);
        if(is_null($crimeregister)){
            return response()->json(["message" => "Record Not found"], 404);
        }
        return response()->json($crimeregister, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Crimeregister  $crimeregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Crimeregister $crimeregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Crimeregister  $crimeregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Crimeregister $crimeregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Crimeregister  $crimeregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crimeregister $crimeregister)
    {
        //
    }

    public function showbyppid($ppid) 
    {
        $data = Crimeregister::orderBy('id','desc')->where('ppid', $ppid)->get();
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
        $data = Crimeregister::orderBy('id','desc')->where('psid', $psid)->get();
        if(is_null($data)){
            return response()->json(["message" => "Record Not found"], 404);
        }
        if($data->isEmpty()){
            return response()->json(["message" => "Record Empty"], 404);
        }
        return response()->json($data, 200);    
    }
}
