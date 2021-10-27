<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            $gender = $request->gender,
            $name = $request->name,
            $caste = $request->caste,
        ];

        return response()->json(["message" => "success", "data" => $data]);
    }
}
