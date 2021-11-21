<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;

class CertificateController extends Controller
{
    public function store(Request $request)
    {

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $ppname = $loggedinuser->name;
        $village = $loggedinuser->village;
        $taluka = $loggedinuser->taluka;
        $name = $request->name;
        $age = $request->age;
        $today = date('d-m-Y');

        $link = "http://127.0.0.1:8000/api/certificate/show?village=$village&taluka=$taluka&name=$name&age=$age&date=$today&ppname=$ppname";
        return response()->json(["message" => "Success", "link" => $link], 200);
    }

    public function show(Request $request)
    {
        $ppname = $request->ppname;
        $village = $request->village;
        $taluka = $request->taluka;
        $name = $request->name;
        $age = $request->age;
        $date = $request->date;
        return view('certificate', compact('village', 'taluka', 'name', 'age', 'ppname', 'date'));
    }
}
