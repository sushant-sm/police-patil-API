<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MandhanController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            $count = $request->count
        ];

        return response()->json(["message" => "success", "data" => $data]);
    }
}
