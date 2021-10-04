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
        if (is_null($missing)) {
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
        $data = $request->validate([
            'isadult' => 'nullable',
            'name' => 'required|string',
            'age' => 'nullable|numeric',
            'gender' => 'required',
            'aadhar' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'address' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'missingdate' => 'required',
            'ppid' => 'required',
            'psid' => 'required',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($uid != $data['ppid']) {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }

        if ($request->hasfile('aadhar')) {
            $file = $request->file('aadhar');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/missingregister/aadhar', $filename);
            $data['aadhar'] = $filename;
        }
        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/missingregister/photo', $filename);
            $data['photo'] = $filename;
        }

        $missing = Missingregister::create($data);

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
        if (is_null($missingregister)) {
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
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Missingregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
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
        $data = Missingregister::orderBy('id', 'desc')->where('psid', $psid)->get();
        if (is_null($data)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        if ($data->isEmpty()) {
            return response()->json(["error" => "Record Empty"], 404);
        }
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
