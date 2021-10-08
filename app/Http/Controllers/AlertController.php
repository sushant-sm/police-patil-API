<?php

namespace App\Http\Controllers;

use App\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alert = Alert::all();
        if (empty($alert)) {
            return response()->json(["error" => "No Alert found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $alert], 200);
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
            'title' => 'required|string',
            'date' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'videolink' => 'nullable|string',
            'otherlink' => 'nullable|string',
            'file' => 'nullable|mimes:pdf',
        ]);

        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/alert/photo/' . time() . '.' . $extension;
            $file->move('uploads/alert/photo', $filename);
            $data['photo'] = $filename;
        }

        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/alert/file/' . time() . '.' . $extension;
            $file->move('uploads/alert/file', $filename);
            $data['file'] = $filename;
        }

        $alert = Alert::create($data);

        return response()->json(["message" => "Success", "data" => $alert], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function show(Alert $alert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function edit(Alert $alert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alert $alert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alert $alert)
    {
        //
    }
}
