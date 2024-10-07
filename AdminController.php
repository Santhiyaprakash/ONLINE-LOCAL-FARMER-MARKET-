<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        return view('welcome');
    }
    public function adminlogin()
    {
        return view("login_admin");
    }
    public function logout(Request $request)
    {
        auth()->logout();

        session()->flash('success', 'You have been successfully logged out.');

        return redirect()->route('index');
    }

     
    public function adminloginpost(Request $request)
    {
        $uname = $request->input('uname');
        $pass = $request->input('pass');

        // Run a raw SQL query to check if the username and password match
        $user = DB::select("SELECT * FROM admins WHERE uname = ? AND pass = ?", [$uname, $pass]);

        if (!empty($user)) {
            // Authentication successful
            Session::put('uname', $uname);

            return response()->json(['success' => true]);
        } else {
            // Authentication failed
            return response()->json(['success' => false]);
        }
    }
    
    public function adminhome()
    {
        return view("adminhome");
    } 
    
    public function showprovider()
    {
        $providers = DB::table('ar_provider')->get();
        return response()->json(['data' => $providers]);
    }
    
    public function provider_approval(Request $request, $id)
    {

    
        $update=DB::table('ar_provider')->where('id', $id)->update(['status' => 1]);
        if($update){


        return response()->json(['message' => 'Provider Apporved']);
        }
    }
    
    public function addservices()
    {
        return view('addservices');
    }
    public function agriservices(Request $request)
{
    
    $service_name = $request->input('service_name');

    $maxid = DB::table('ar_service')->max('id') + 1;
    $maxid = $maxid ?? 1;
    
    $now = now();
    $create_date = $now->format('d-m-Y');

    $regser=DB::table('ar_service')->insert([
        'id' => $maxid,
        'service_name' => $service_name,
        'rdate' => $create_date,
        
        


    ]);

    if ($regser) {
       

        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false]);
    }
}

    public function allbookservices(Request $request)
    {
        $serreq = DB::table('ar_apply_service')->get();

        return response()->json(['data' => $serreq]);
    }
    
    public function serapprove(Request $request, $id)
    {

    
        $update=DB::table('ar_apply_service')->where('id', $id)->update(['status' => 1]);
        if($update){


        return response()->json(['message' => 'Service Provider Apporved']);
        }
    }

   
    public function adminviewvehicle()
    {
        return view('admin_view_vehicle');
    }
    

    
    public function adminviewvehiclelist(Request $request, $id)
    {
        $providers = DB::table('ar_vehicle')
        ->where('id', $id)
        ->get();

        return response()->json(['data' => $providers]);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
