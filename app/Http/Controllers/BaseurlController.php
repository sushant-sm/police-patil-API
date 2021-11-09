<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseurlController extends Controller
{
    public function index()
    {
        $baseurl = "https://pp.thesupernest.com";
        return response()->json(["baseurl" => $baseurl], 200);
    }
}
