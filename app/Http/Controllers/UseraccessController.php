<?php

namespace App\Http\Controllers;

use App\Useraccess;
use Illuminate\Http\Request;
use Validator;

class UseraccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arms = Useraccess::get();
        if(is_null($arms)){
            return response()->json(["message" => "No Arms found"], 404);
        }
        return response()->json($arms, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return response()->json(["messege"=>"sadf"]);
        
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
            'user_id' => 'required',
            'tables' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        $arms = Useraccess::create($request->all());

        return response()->json($arms, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Useraccess  $useraccess
     * @return \Illuminate\Http\Response
     */
    public function show(Useraccess $useraccess)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Useraccess  $useraccess
     * @return \Illuminate\Http\Response
     */
    public function edit(Useraccess $useraccess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Useraccess  $useraccess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Useraccess $useraccess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Useraccess  $useraccess
     * @return \Illuminate\Http\Response
     */
    public function destroy(Useraccess $useraccess)
    {
        //
    }

    public function useraccesstable(Useraccess $useraccess, $user_id) {
        $data = Useraccess::orderBy('id','desc')->where('user_id', $user_id)->get(["tables"]);
        return response()->json($data);
    }
}
