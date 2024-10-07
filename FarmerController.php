<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class FarmerController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     */
    public function signup(Request $request)
    {
        
        $name = $request->input('name');
        $address = $request->input('address');
        $district = $request->input('district');
        $mobile = $request->input('mobile');
        $email = $request->input('email');
        $uname = $request->input('uname');
        $pass = $request->input('pass');
       

        $maxid = DB::table('ar_user')->max('id') + 1;
        $maxid = $maxid ?? 1;
       
        $farmer=DB::table('ar_user')->insert([
            'id' => $maxid,
            'name' => $name,
            'address' => $address,
            'district' => $district,
            'mobile' => $mobile,
            'email' => $email,
            'uname' => $uname,
            'pass' => $pass,
           


        ]);

        if ($farmer) {
           

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
        
    }
    
    public function loginfarmer(Request $request)
    {
        $uname = $request->input('uname');
        $pass = $request->input('pass');

        // Run a raw SQL query to check if the username and password match
        $user = DB::select("SELECT * FROM ar_user WHERE uname = ? AND pass = ?", [$uname, $pass]);

        if (!empty($user)) {
            // Authentication successful
            Session::put('uname', $uname);

            return response()->json(['success' => true]);
        } else {
            // Authentication failed
            return response()->json(['success' => false]);
        }
    }
    
    public function userhome()
    {
        return view("farmerhome");
    }
    
    public function allvehicles(Request $request)
    {
        $providers = DB::table('ar_vehicle')->where('status', 0)->get();

        return response()->json(['data' => $providers]);
    }
    public function searchVehicles(Request $request)
    {
        $search = $request->input('search');
        $uname = $request->input('uname');
    
        $gs = '%' . $search . '%';
    
        $uu = [];
        $dd2 = DB::table('ar_provider')
            ->where('name', 'like', $gs)
            ->orWhere('address', 'like', $gs)
            ->orWhere('district', 'like', $gs)
            ->get();
    
        foreach ($dd2 as $ds2) {
            $uu[] = $ds2->uname;
        }
    
        $data2 = [];
        if (count($uu) > 0) {
            foreach ($uu as $u1) {
                $dd3 = DB::table('ar_vehicle')
                    ->where('uname', $u1)
                    ->where('status', 0)
                    ->get();
    
                $data2 = array_merge($data2, $dd3->toArray());
            }
        } else {
            $data2 = DB::table('ar_vehicle')
                ->where(function ($query) use ($gs) {
                    $query->where('uname', 'like', $gs)
                        ->orWhere('vehicle', 'like', $gs)
                        ->orWhere('vno', 'like', $gs)
                        ->orWhere('details', 'like', $gs);
                })
                ->where('status', 0)
                ->get()
                ->toArray();
        }
    
        return response()->json(['data2' => $data2]);
    }
    public function booking()
    {
        return view("book");
    }
    
    public function providerlist(Request $request, $id)
    {
        $vechown = DB::table('ar_vehicle')->where('id', $id)->first();
        
        if (!$vechown) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }
        
        $uname = $vechown->uname;
        $providers = DB::table('ar_provider')->where('uname', $uname)->get();

        if ($providers->isNotEmpty()) {
            return response()->json(['data' => $providers]);
        } else {
            return response()->json(['error' => 'Provider not found'], 404);
        }
    }

    public function bookingpost(Request $request)
    {
       
        $pid = $request->input('pid');
        $storedUname = $request->input('storedUname');
        $duration = $request->input('duration');
        $time_type = $request->input('time_type');
        $req_date = $request->input('req_date');
        
        $vehdet = DB::table('ar_vehicle')->where('id', $pid)->first(); // Use first() to get a single record
        $u = $vehdet->uname;
        $cost1 = $vehdet->cost1;
        $cost2 = $vehdet->cost2;
        

        $maxid = DB::table('ar_booking')->max('id') + 1;
        $maxid = $maxid ?? 1;
        $tt = (int)$duration;

        if ($time_type == "1") {
            $amt = $cost1 * $tt;
        } else {
            $amt = $cost2 * $tt;
        }
       
       
        $booking=DB::table('ar_booking')->insert([
            'id' => $maxid,
            'uname' => $storedUname,
            'provider' => $u,
            'vid' => $pid,
            'duration' => $duration,
            'time_type' => $time_type,
            'req_date' => $req_date,
            'status' => 0,
            'amount' => $amt,
            'pay_st' => 0,
           


        ]);

        if ($booking) {
           

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }

    }
    public function status($uname)
{
    $user = DB::select('SELECT * FROM ar_user WHERE uname = ?', [$uname]);

    if (!empty($user)) {
        $data = $user[0]; 

       /*  $data2 = DB::select('SELECT ar_vehicle.* FROM ar_vehicle
                JOIN ar_booking ON ar_vehicle.id = ar_booking.vid
                WHERE ar_booking.uname = ? 
                ORDER BY ar_booking.id DESC', [$uname]); */
                $data2 = DB::table('ar_vehicle')
                ->join('ar_booking', 'ar_vehicle.id', '=', 'ar_booking.vid')
                ->where('ar_booking.uname', '=', $uname)
                ->orderBy('ar_booking.id', 'desc')
                ->select('ar_vehicle.details', 'ar_vehicle.vno', 'ar_vehicle.vehicle', 'ar_vehicle.cost1', 'ar_vehicle.cost2', 'ar_vehicle.photo', 'ar_booking.id', 'ar_booking.vid', 'ar_booking.provider', 'ar_booking.duration', 'ar_booking.time_type', 'ar_booking.req_date', 'ar_booking.status', 'ar_booking.amount')
                ->get();


        // Return a JSON response with user and vehicle/booking data
        return response()->json([
            'user' => $data,
            'vehicle_booking' => $data2,
        ]);
    } else {
        // User not found, return an error response
        return response()->json([
            'error' => 'User not found',
        ], 404);
    }
}

        public function user_status()
        {
            return view("user_status");
        }

        public function user_pay()
    {
        return view("user_pay");
    }
    
    public function payment(Request $request)
{
    $bid = $request->input('bid');
    $storedUname = $request->input('storedUname');
    $vid = $request->input('vid');
    DB::table('ar_booking')
            ->where('id', $bid)
            ->where('uname', $storedUname)
            ->update(['status' => 2]);
        
        DB::table('ar_vehicle')
            ->where('id', $vid)
            ->update(['status' => 0]);


    return response()->json(['success' => 'Paid']);

}

public function farmerprofile()
{
    return view("farmerprofile");
}

public function farmerprofile1($uname)
{
    $farmers = DB::table('ar_user')->where('uname', $uname)->first();
    
    if ($farmers) {
        return response()->json($farmers);
    } else {
        return response()->json(['error' => 'farmer not found'], 404);
    }
}

    public function addproductpost(Request $request)
    {
        
        $uname = $request->input('uname');
        $product = $request->input('product');
        $qty = $request->input('qty');
        $price = $request->input('price');
       
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $folderPath = public_path('/uploads/products');
            $file->move($folderPath, $fileName);
            
        
       
        $maxid = DB::table('ar_product')->max('id') + 1;
        $maxid = $maxid ?? 1;
        $now = now();
        $create_date = $now->format('d-m-Y');
        $vegpro=DB::table('ar_product')->insert([
            'id' => $maxid,
            'product' => $product,
            'farmer' => $uname,
            'qty' => $qty,
            'price' => $price,
            'pfile' => $fileName,
            'rdate' => $create_date,
            'status' => '0',


        ]);

        if ($vegpro) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    } else {
        return response()->json(['success' => false]);
    }
    }
    
    public function addproduct()
    {
        return view("addproduct");
    }
     
/* public function products($uname)
{
    $products = DB::table('ar_product')
        ->where('farmer', $uname)
        ->get();

    if ($products->isNotEmpty()) {
        return response()->json([
            'products' => $products,
           
        ]);
    } else {
        return response()->json([
            'error' => 'No Products found for the Farmer',
        ], 404);
    }
} */

public function products($uname)
{
    $products = DB::table('ar_product')
        ->select('ar_product.*', 'ar_product_booking.uname', 'ar_product_booking.farmer', 'ar_product_booking.req_date', 'ar_product_booking.fid', 'ar_product_booking.qty', 'ar_product_booking.status', 'ar_product_booking.amount')
        ->join('ar_product_booking', 'ar_product.id', '=', 'ar_product_booking.fid')
        ->where('ar_product.farmer', $uname)
        
        ->get();

    if ($products->isNotEmpty()) {
        return response()->json([
            'products' => $products,
            'count' => $products->count(),
        ]);
    } else {
        return response()->json([
            'error' => 'No products found for the user',
        ], 404);
    }
}

    public function productinfo()
    {
        return view("productinfo");
    }
      
    public function bookings($uname)
    {
        $booking = DB::table('ar_product_booking')
            ->where('farmer', $uname)
            ->get();
    
        if ($booking->isNotEmpty()) {
            return response()->json([
                'booking' => $booking,
               
            ]);
        } else {
            return response()->json([
                'error' => 'No booking found for the Farmer',
            ], 404);
        }
    }
    public function delivery_request($id)
    {
    DB::table('ar_product_booking')
            ->where('id', $id)
            ->update(['status' => 2]);
        
       


    return response()->json(['success' => 'Delivered']);

    }
    public function products1($uname)
{
    $products = DB::table('ar_product')
               ->where('farmer', $uname)
               ->get();

    if ($products->isNotEmpty()) {
        return response()->json([
            'products' => $products,
            'count' => $products->count(),
        ]);
    } else {
        return response()->json([
            'error' => 'No products found for the user',
        ], 404);
    }
}

    public function updateproducts(Request $request)
    {
        $id = $request->query('id');
        $product = $request->query('product');


        return view('updateproducts', ['id' => $id, 'product' => $product]);
    }
      

public function updateproductpost(Request $request)
{
    try {
        $id = $request->input('id');
        $qty = $request->input('qty');
        $price = $request->input('price');

        // Check if the file is present
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $folderPath = public_path('/uploads/products');
            $file->move($folderPath, $fileName);

            Log::info('Product Update Request Data:', [
                'id' => $id,
                'qty' => $qty,
                'price' => $price,
                'pfile' => $fileName,
            ]);

            $vegpro = DB::table('ar_product')
                ->where('id', $id)
                ->update([
                    'qty' => $qty,
                    'price' => $price,
                    'pfile' => $fileName,
                ]);

            if ($vegpro) {
                // Success case
                Session::flash('success', 'Product updated successfully');
            } else {
                // Failure case
                Session::flash('error', 'Failed to update product');
            }
        } else {
            // Handle the case where the file is not provided
            Session::flash('error', 'File not provided');
        }

        return redirect()->route('updateproducts'); // Assuming you have a route named 'updateproduct'
    } catch (\Exception $e) {
        Log::error("An error occurred: " . $e->getMessage());
        Session::flash('error', 'An error occurred');
        return redirect()->route('updateproducts');
    }
}

    
        public function faraddservices()
        {
            return view("faraddservices");
        }
        
        public function farservicebooking()
        {
            return view("farservicebooking");
        }
        public function farservbookingpost(Request $request)
        {
        
            $uname = $request->input('uname');
            $servicepro = $request->input('servicepro');
            $sid = $request->input('sid');
            $duration = $request->input('duration');
            $time_type = $request->input('time_type');
            $req_date = $request->input('req_date');
            $maxid = DB::table('farmer_service_booking')->max('id') + 1;
            $maxid = $maxid ?? 1;
            $now = now();
            $create_date = $now->format('d-m-Y');
            $booking=DB::table('farmer_service_booking')->insert([
                'id' => $maxid,
                'uname' => $uname,
                'provider' => $servicepro,
                'sid' => $sid,
                'duration' => $duration,
                'time_type' => $time_type,
                'req_date' => $req_date,
                'status' => 0,
                'amount' => 0,
                'rdate' => $create_date,
            


            ]);

            if ($booking) {
            

                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }

        }

        
        public function farserviceinfo()
        {
            return view("farserviceinfo");
        }
        
        public function faragriservices($uname)
        {
            $products = DB::table('farmer_service_booking')
            ->select('farmer_service_booking.*', 'ar_user.address', 'ar_user.mobile', 'ar_user.email', 'ar_apply_service.serviceid', 'farmer_service_booking.sid', 'ar_apply_service.service')
            ->join('ar_user', 'farmer_service_booking.uname', '=', 'ar_user.uname')
            ->join('ar_apply_service', 'ar_apply_service.serviceid', '=', 'farmer_service_booking.sid') 
            ->where('farmer_service_booking.uname', $uname)
            ->get();
        
            if ($products->isNotEmpty()) {
                return response()->json([
                    'products' => $products,
                    'count' => $products->count(),
                ]);
            } else {
                return response()->json([
                    'error' => 'No booking found for the user',
                ], 404);
            }
        }
    public function farpaidbill()
    {
        return view("farpaidbill");
    }
    
    public function Farmerpaid(Request $request)
{
    $id = $request->input('id');
    $storedUname = $request->input('storedUname');
    $amount = $request->input('amount');
    DB::table('farmer_service_booking')
            ->where('id', $id)
            ->where('uname', $storedUname)
            ->where('amount', $amount)
            ->update(['status' => 2]);
        
        


    return response()->json(['success' => 'Paid']);

}

    public function farviewlivestocks()
    {
        return view("farviewlivestocks");
    }
    public function deleteProduct($id)
    {
        try {
            DB::table('ar_product')->where('id', $id)->delete();

            return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete product']);
        }
    }

    
    public function bookinglivestocks()
    {
        return view("bookinglivestocks");
    }

     
    public function livestockproviderlist(Request $request, $id)
    {
        $vechown = DB::table('ar_live_stock_booking')->where('id', $id)->first();
        
        if (!$vechown) {
            return response()->json(['error' => 'livestock not found'], 404);
        }
        
        $uname = $vechown->provider;
        $providers = DB::table('ar_provider')->where('uname', $uname)->get();

        if ($providers->isNotEmpty()) {
            return response()->json(['data' => $providers]);
        } else {
            return response()->json(['error' => 'Provider not found'], 404);
        }
    }

    
    public function livestockbookingpost(Request $request)
    {
        
        $livestockid = $request->input('livestockid');
        $uname = $request->input('uname');
        $utype = $request->input('utype');
        $req_date = $request->input('req_date');
        $amount = $request->input('amount');
        $vehdet = DB::table('ar_live_stock_booking')->where('id', $livestockid)->first(); // Use first() to get a single record
        $u = $vehdet->provider;
        
        $maxid = DB::table('ar_users_live_stock_booking')->max('id') + 1;
        $maxid = $maxid ?? 1;
        $now = now();
        $create_date = $now->format('d-m-Y');
        

        $booking=DB::table('ar_users_live_stock_booking')->insert([
            'id' => $maxid,
            'uname' => $uname,
            'provider' => $u,
            'livestockid' => $livestockid,
            'utype' => $utype,
            'req_date' => $req_date,
            'status' => 0,
            'amount' => $amount,
            'rdate' => $create_date,
           


        ]);

        if ($booking) {
           

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
   
    }

    
    public function farlivestockstatus()
    {
        return view("farlivestockstatus");
    }
    

    
        public function farlivestocksbooking($uname)
        {
            $livestocks = DB::table('ar_users_live_stock_booking')
                ->select('ar_users_live_stock_booking.*', 'ar_user.address', 'ar_user.mobile', 'ar_user.email', 'ar_live_stock_booking.id', 'ar_users_live_stock_booking.livestockid', 'ar_live_stock_booking.animal')
                ->join('ar_user', 'ar_users_live_stock_booking.uname', '=', 'ar_user.uname')
                ->join('ar_live_stock_booking', 'ar_live_stock_booking.id', '=', 'ar_users_live_stock_booking.livestockid') 
                ->where('ar_users_live_stock_booking.uname', $uname)
                ->where('ar_users_live_stock_booking.utype', 'Farmer')

                ->get();

                if ($livestocks->isNotEmpty()) {
                    return response()->json([
                        'livestocks' => $livestocks,
                        'count' => $livestocks->count(),
                    ]);
                } else {
                    return response()->json([
                        'error' => 'No booking found for the user',
                    ], 404);
                }
                            
        
        }

             
        public function farlivestockpaidbill()
        {
            return view("farlivestockpaidbill");
        }

        
        
        public function farlivestockpaidbill_post(Request $request)
    {
        $id = $request->input('id');
        $storedUname = $request->input('storedUname');
        $amount = $request->input('amount');
        DB::table('ar_users_live_stock_booking')
                ->where('id', $id)
                ->where('uname', $storedUname)
                ->where('amount', $amount)
                ->update(['status' => 1]);
            
            
    
    
        return response()->json(['success' => 'Paid']);
    
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
