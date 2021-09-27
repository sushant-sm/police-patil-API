<?php

namespace App\Http\Controllers;

use App\Armsregister;
use Validator;
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
        if(is_null($arms)){
            return response()->json(["error" => "No Arms found"], 404);
        }
        return response()->json(["message" => "Success", "data"=>$arms], 200);
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
        $rules = [
            'type' => 'required|string',
            'name' => 'required|string',
            'mobile' => 'required|numeric|digits:10',
            'aadhar' => 'required',
            'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'licencenumber' => 'required',
            'validity' => 'required|date',
            'licencephoto' => 'required',
            'ppid' => 'required',
            'psid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        $arms = Armsregister::create($request->all());

        return response()->json(["message" => "Success", "data"=>$arms], 201);
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
        if(is_null($armsregister)){
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

        if($ppid == $uid)
        {
            $data = Armsregister::orderBy('id','desc')->where('ppid', $ppid)->get();
            if(is_null($data)){
                return response()->json(["error" => "Record Not found"], 404);
            }
            if($data->isEmpty()){
                return response()->json(["error" => "Record Empty"], 404);
            }
            return response()->json(["message" => "Success", "data"=>$data], 200); 

        }
        else 
        {
            return response()->json(["error" => "Your Not authorised Person"], 404); 
        }
        
           
    }

    public function showbypsid($psid) 
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if($psid == $uid)
        {
            $data = Armsregister::orderBy('id','desc')->where('psid', $psid)->get();
            if(is_null($data)){
                return response()->json(["error" => "Record Not found"], 404);
            }
            if($data->isEmpty()){
                return response()->json(["error" => "Record Empty"], 404);
            }
            return response()->json(["message" => "Success", "data"=>$arms], 200);    
        }
        else 
        {
            return response()->json(["error" => "Your Not authorised Person"], 200); 
        }
    }
} 
