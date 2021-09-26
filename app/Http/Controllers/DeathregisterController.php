<?php

namespace App\Http\Controllers;

use App\Deathregister;
use Illuminate\Http\Request;

class DeathregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $death = Deathregister::get();
        if(is_null($death)){
            return response()->json(["message" => "No Arms found"], 404);
        }
        return response()->json($death, 200);
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
        $death = Deathregister::create($request->all());

        return response()->json($death, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deathregister  $deathregister
     * @return \Illuminate\Http\Response
     */
    public function show(Deathregister $deathregister, $id)
    {
        $death = Deathregister::find($id);
        if(is_null($death)){
            return response()->json(["message" => "Record Not found"], 404);
        }
        return response()->json($death, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deathregister  $deathregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Deathregister $deathregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deathregister  $deathregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deathregister $deathregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deathregister  $deathregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deathregister $deathregister)
    {
        //
    }

    public function showbyppid($ppid) 
    {
        $data = Deathregister::orderBy('id','desc')->where('ppid', $ppid)->get();
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
        $data = Deathregister::orderBy('id','desc')->where('psid', $psid)->get();
        if(is_null($data)){
            return response()->json(["message" => "Record Not found"], 404);
        }
        if($data->isEmpty()){
            return response()->json(["message" => "Record Empty"], 404);
        }
        return response()->json($data, 200);    
    }
}
