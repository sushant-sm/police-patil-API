<?php

namespace App\Http\Controllers;

use App\Policestation;
use Illuminate\Http\Request;

class PolicestationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ps = Policestation::get();
        return response()->json(["message" => "Success", "data" => $ps]);
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
            'psname' => 'required|string',
            'address' => 'nullable',
        ]);

        $missing = Policestation::create($data);

        return response()->json(["message" => "Success", "data" => $missing], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Policestation  $policestation
     * @return \Illuminate\Http\Response
     */
    public function show(Policestation $policestation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Policestation  $policestation
     * @return \Illuminate\Http\Response
     */
    public function edit(Policestation $policestation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Policestation  $policestation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Policestation $policestation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Policestation  $policestation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Policestation $policestation)
    {
        //
    }
}
