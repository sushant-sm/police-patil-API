<?php

namespace App\Http\Controllers;

use App\Alltable;
use Illuminate\Http\Request;

class AlltableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tables = Alltable::get();
        return response()->json(["message" => "Success", "data" => $tables], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $data = $request->validate([
        //     'type' => 'required|string',
        //     'name' => 'required|string',
        //     'mobile' => 'nullable|numeric|digits:10',
        //     'aadhar' => 'nullable|image|mimes:jpg,png,jpeg,svg',
        //     'address' => 'nullable',
        //     'latitude' => 'nullable',
        //     'longitude' => 'nullable',
        //     'licencenumber' => 'nullable',
        //     'validity' => 'nullable',
        //     'licencephoto' => 'nullable|image|mimes:jpg,png,jpeg,svg',
        //     'ppid' => 'required',
        //     'psid' => 'required'
        // ]);




        // $arms = Armsregister::create($data);

        // return response()->json(["message" => "Success", "data" => $arms], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Alltable  $alltable
     * @return \Illuminate\Http\Response
     */
    public function show(Alltable $alltable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Alltable  $alltable
     * @return \Illuminate\Http\Response
     */
    public function edit(Alltable $alltable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Alltable  $alltable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alltable $alltable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Alltable  $alltable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alltable $alltable)
    {
        //
    }
}
