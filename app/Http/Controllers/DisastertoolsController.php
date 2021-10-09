<?php

namespace App\Http\Controllers;

use App\Disastertools;
use Illuminate\Http\Request;

class DisastertoolsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $disaster = Disastertools::get();
        if (is_null($disaster)) {
            return response()->json(["error" => "No Tools found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $disaster], 200);
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
            'name' => 'required|string',
            'quantity' => 'nullable|string',
            'type' => 'nullable',
            'ppid' => 'required',
            'psid' => 'required'
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($uid != $data['ppid']) {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }

        $disaster = Disastertools::create($data);

        return response()->json(["message" => "Success", "data" => $disaster], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Disastertools  $disastertools
     * @return \Illuminate\Http\Response
     */
    public function show(Disastertools $disastertools)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Disastertools  $disastertools
     * @return \Illuminate\Http\Response
     */
    public function edit(Disastertools $disastertools)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Disastertools  $disastertools
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disastertools $disastertools)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Disastertools  $disastertools
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disastertools $disastertools)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Disastertools::orderBy('id', 'desc')->where('ppid', $ppid)->get();
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
        $data = Disastertools::orderBy('id', 'desc')->where('psid', $psid)->get();
        if (is_null($data)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        if ($data->isEmpty()) {
            return response()->json(["error" => "Record Empty"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
