<?php

namespace App\Http\Controllers;

use App\Illegalworkregister;
use Validator;
use Illuminate\Http\Request;

class IllegalworkregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $illegalwork = Illegalworkregister::get();
        if(is_null($illegalwork)){
            return response()->json(["error" => "No Arms found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $illegalwork], 200);
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
            'address' => 'required|string',
            'ppid' => 'required',
            'psid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        $illegalwork = Illegalworkregister::create($request->all());

        return response()->json(["message" => "Success", "data" => $illegalwork], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Illegalworkregister  $illegalworkregister
     * @return \Illuminate\Http\Response
     */
    public function show(Illegalworkregister $illegalworkregister, $id)
    {
        $illegelworkregister = Illegalworkregister::find($id);
        if(is_null($illegelworkregister)){
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $illegalworkregister], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Illegalworkregister  $illegalworkregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Illegalworkregister $illegalworkregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Illegalworkregister  $illegalworkregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Illegalworkregister $illegalworkregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Illegalworkregister  $illegalworkregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Illegalworkregister $illegalworkregister)
    {
        //
    }

    public function showbyppid($ppid) 
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if($ppid == $uid)
        {
            $data = Illegalworkregister::orderBy('id','desc')->where('ppid', $ppid)->get();
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
        $data = Illegalworkregister::orderBy('id','desc')->where('psid', $psid)->get();
        if(is_null($data)){
            return response()->json(["error" => "Record Not found"], 404);
        }
        if($data->isEmpty()){
            return response()->json(["error" => "Record Empty"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);    
    }
}
