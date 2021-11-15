<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(["message" => "Success", "minversion" => "0.0.0", "latestversion" => "1.0.0+1", "note" => "Some Message"], 200);

        // $fresh = "1.1.0";
        // $fine = array("1.0.8", "1.0.7");
        // $old = array("1.0.0", "1.0.6");

        // $version = $request->version;

        // if ($version == $fresh) {
        //     return response()->json(["message" => "Success", "status" => "fresh"], 200);
        // } else {
        //     $status = "";
        //     foreach ($fine as $fine) {
        //         if ($version == $fine) {
        //             $status = "fine";
        //         }
        //     }
        //     if ($status == "fine") {
        //         return response()->json(["message" => "Success", "status" => "fine"], 200);
        //     } else {
        //         foreach ($old as $old) {
        //             if ($version == $old) {
        //                 $status = "old";
        //             }
        //         }
        //         if ($status == "old") {
        //             return response()->json(["message" => "Success", "status" => "old"], 200);
        //         }
        //     }
        // }
    }
}
