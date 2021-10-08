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
        if (is_null($movement)) {
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
        $data = $request->validate([
            'type' => 'required|string',
            'subtype' => 'required|string',
            'address' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'datetime' => 'nullable',
            'essue' => 'nullable|boolean',
            'attendance' => 'nullable|integer',
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
            $filename = 'pp.thesupernest.com/uploads/movementregister/' . time() . '.' . $extension;
            $file->move('uploads/movementregister', $filename);
            $data['photo'] = $filename;
        }

        $movement = Movementregister::create($data);

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
        if (is_null($movement)) {
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

        if ($ppid == $uid) {
            $data = Movementregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
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
        $data = Movementregister::orderBy('id', 'desc')->where('psid', $psid)->get();
        if (is_null($data)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        if ($data->isEmpty()) {
            return response()->json(["error" => "Record Empty"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
