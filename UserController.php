<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Add this line to import the Log facade


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function customerlogin()
    {
        return view("login_customer");
    }
    
    
    public function customerregister()
    {
        return view("register_customer");
    }
    public function registercus(Request $request)
    {
        try {
            $name = $request->input('name');
            $address = $request->input('address');
            $district = $request->input('district');
            $mobile = $request->input('mobile');
            $email = $request->input('email');
            $uname = $request->input('uname');
            $pass = $request->input('pass');
    
            $maxid = DB::table('ar_customer')->max('id') + 1;
            $maxid = $maxid ?? 1;
    
            $now = now();
            $create_date = $now->format('d-m-Y');
            Log::info('Customer register:', [
                'id' => $maxid,
                'name' => $name,
                'address' => $address,
                'district' => $district,
                'mobile' => $mobile,
                'email' => $email,
                'uname' => $uname,
                'pass' => $pass,
                'create_date' => $create_date,
                'status' => '0',
            ]);
    
            $regcus = DB::table('ar_customer')->insert([
                'id' => $maxid,
                'name' => $name,
                'address' => $address,
                'district' => $district,
                'mobile' => $mobile,
                'email' => $email,
                'uname' => $uname,
                'pass' => $pass,
                'create_date' => $create_date,
                'status' => '0',
            ]);
    
            if ($regcus) {
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            Log::error('Customer Registration Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Internal Server Error'], 500);
        }
    }
    public function customerloginpost(Request $request)
    {
        $uname = $request->input('uname');
        $pass = $request->input('pass');

        // Run a raw SQL query to check if the username and password match
        $user = DB::select("SELECT * FROM ar_customer WHERE uname = ? AND pass = ?", [$uname, $pass]);

        if (!empty($user)) {
            // Authentication successful
            Session::put('uname', $uname);

            return response()->json(['success' => true]);
        } else {
            // Authentication failed
            return response()->json(['success' => false]);
        }
    }
    public function allproducts(Request $request)
    {
        $products = DB::table('ar_product')->where('status', 0)->get();

        return response()->json(['data' => $products]);
    }
    public function customerhome()
    {
        return view("customerhome");
    }
    public function searchproducts(Request $request)
    {
        $search = $request->input('search');
        $uname = $request->input('uname');
    
        $gs = '%' . $search . '%';
    
        $uu = [];
        $dd2 = DB::table('ar_user')
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
                $dd3 = DB::table('ar_product')
                    ->where('uname', $u1)
                    ->where('status', 0)
                    ->get();
    
                $data2 = array_merge($data2, $dd3->toArray());
            }
        } else {
            $data2 = DB::table('ar_product')
                ->where(function ($query) use ($gs) {
                    $query->where('farmer', 'like', $gs)
                        ->orWhere('product', 'like', $gs);
                        
                })
                ->where('status', 0)
                ->get()
                ->toArray();
        }
    
        return response()->json(['data2' => $data2]);
    }
    public function productbook()
    {
        return view("productbook");
    }
    public function productlist(Request $request, $fid)
    {
        $productown = DB::table('ar_product')->where('id', $fid)->first();
        
        if (!$productown) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        $uname = $productown->farmer;
        $products = DB::table('ar_user')->where('uname', $uname)->get();

        if ($products->isNotEmpty()) {
            return response()->json(['data' => $products]);
        } else {
            return response()->json(['error' => 'product not found'], 404);
        }
    }
    
    public function productbookingpost(Request $request)
{
    try {
        $fid = $request->input('fid');
        $uname = $request->input('storedUname');
        $qty = $request->input('qty');
        $req_date = $request->input('req_date');

        $vehdet = DB::table('ar_product')->where('id', $fid)->first();

        if ($vehdet) {
            $u = $vehdet->farmer;
            $productQty = $vehdet->qty;
            $price = $vehdet->price;

            if ($productQty == 0) {
                return response()->json(['success' => false, 'error1' => 'Product is empty. Cannot book the product.'], 422);
            }

            $amount = $price * $qty;

            $maxid = DB::table('ar_product_booking')->max('id') + 1;
            $maxid = $maxid ?? 1;

            Log::info('Job Poster Update Request Data:', [
                'id' => $maxid,
                'uname' => $uname,
                'farmer' => $u,
                'fid' => $fid,
                'qty' => $qty,
                'req_date' => $req_date,
                'status' => 0,
                'amount' => $amount,
            ]);

            $booking = DB::table('ar_product_booking')->insert([
                'id' => $maxid,
                'uname' => $uname,
                'farmer' => $u,
                'fid' => $fid,
                'qty' => $qty,
                'req_date' => $req_date,
                'status' => 0,
                'amount' => $amount,
            ]);

            if ($booking) {
                $updatedQty = $productQty - $qty;

                DB::table('ar_product')
                    ->where('id', $fid)
                    ->update(['qty' => $updatedQty]);

                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'error' => 'Booking failed. Please try again.'], 422);
            }
        } else {
            return response()->json(['success' => false, 'error' => 'Product details not found.'], 404);
        }
    } catch (\Exception $e) {
        Log::error('Booking Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => 'Internal Server Error'], 500);
    }
}

    public function mybooking()
    {
        return view("mybooking");
    }
    
public function mybookings($uname)
{
    $bookings = DB::table('ar_product_booking')
        ->select('ar_product_booking.*', 'ar_product.product', 'ar_product.farmer', 'ar_product.pfile')
        ->join('ar_product', 'ar_product_booking.fid', '=', 'ar_product.id')
        ->where('ar_product_booking.uname', $uname)
        ->get();

    if ($bookings->isNotEmpty()) {
        return response()->json([
            'bookings' => $bookings,
            'count' => $bookings->count(),
        ]);
    } else {
        return response()->json([
            'error' => 'No Booking found for the user',
        ], 404);
    }
}

    public function productpay()
    {
        return view("productpay");
    }
    public function productpayment(Request $request)
    {
    $id = $request->input('id');
    $customer = $request->input('customer');
    DB::table('ar_product_booking')
            ->where('id', $id)
            ->where('uname', $customer)
            ->update(['status' => 1]);
        
       


    return response()->json(['success' => 'Paid']);

    }
    
    public function cusaddservices()
    {
        return view("cusaddservices");
    }
    
    public function allbookservices1(Request $request)
    {
        $serreq = DB::table('ar_apply_service')->where('status', 1 )->get();

        return response()->json(['data' => $serreq]);
    }
    
    public function cusservicebooking()
    {
        return view("cusservicebooking");
    }
    
    public function providerlist1(Request $request, $servicepro)
    {
        $vechown = DB::table('ar_apply_service')->where('provider', $servicepro)->first();
        
        if (!$vechown) {
            return response()->json(['error' => 'Service not found'], 404);
        }
        
        $uname = $vechown->provider;
        $providers = DB::table('ar_provider')->where('uname', $uname)->get();

        if ($providers->isNotEmpty()) {
            return response()->json(['data' => $providers]);
        } else {
            return response()->json(['error' => 'Provider not found'], 404);
        }
    }
    
    public function servbookingpost(Request $request)
    {
       
        $uname = $request->input('uname');
        $servicepro = $request->input('servicepro');
        $sid = $request->input('sid');
        $duration = $request->input('duration');
        $time_type = $request->input('time_type');
        $req_date = $request->input('req_date');
        $maxid = DB::table('ar_service_booking')->max('id') + 1;
        $maxid = $maxid ?? 1;
        $now = now();
        $create_date = $now->format('d-m-Y');
        $booking=DB::table('ar_service_booking')->insert([
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
    
    public function cusserviceinfo()
    {
        return view("cusserviceinfo");
    }
       
    public function cusagriservices($uname)
    {
        $products = DB::table('ar_service_booking')
        ->select('ar_service_booking.*', 'ar_customer.address', 'ar_customer.mobile', 'ar_customer.email', 'ar_apply_service.serviceid', 'ar_service_booking.sid', 'ar_apply_service.service')
        ->join('ar_customer', 'ar_service_booking.uname', '=', 'ar_customer.uname')
        ->join('ar_apply_service', 'ar_apply_service.serviceid', '=', 'ar_service_booking.sid') 
        ->where('ar_service_booking.uname', $uname)
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
    
    public function cuspaidbill()
    {
        return view("cuspaidbill");
    }
    
    public function Customerpaid(Request $request)
{
    $id = $request->input('id');
    $storedUname = $request->input('storedUname');
    $amount = $request->input('amount');
    DB::table('ar_service_booking')
            ->where('id', $id)
            ->where('uname', $storedUname)
            ->where('amount', $amount)
            ->update(['status' => 2]);
        
        


    return response()->json(['success' => 'Paid']);

}

    public function cusviewlivestocks()
    {
        return view("cusviewlivestocks");
    }

    
    public function cuslivestockstatus()
    {
        return view("cuslivestockstatus");
    }
    

    public function cuslivestocksbooking($uname)
    {
        $livestocks = DB::table('ar_users_live_stock_booking')
            ->select(
                'ar_users_live_stock_booking.*',
                'ar_customer.address',
                'ar_customer.mobile',
                'ar_customer.email',
                'ar_live_stock_booking.id as livestock_id',
                'ar_live_stock_booking.animal'
            )
            ->join('ar_customer', 'ar_users_live_stock_booking.uname', '=', 'ar_customer.uname')
            ->join('ar_live_stock_booking', 'ar_live_stock_booking.id', '=', 'ar_users_live_stock_booking.livestockid')
            ->where('ar_users_live_stock_booking.uname', $uname)
            ->where('ar_users_live_stock_booking.utype', 'Customer')
            ->get();
    
        if ($livestocks->isNotEmpty()) {
            // Assuming there could be multiple records, so use a loop or take the first one
            foreach ($livestocks as $livestock) {
                $getid = $livestock->livestockid;
                // Use the alias set in the select statement
                $ani = $livestock->animal;
    
                return response()->json([
                    'livestocks' => $livestocks,
                    'count' => $livestocks->count(),
                    'animals' => $ani,
                ]);
            }
        } else {
            return response()->json([
                'error' => 'No booking found for the user',
            ], 404);
        }
    }
    

        public function cuslivestockpaidbill()
        {
            return view("cuslivestockpaidbill");
        }

        
        
        public function cuslivestockpaidbill_post(Request $request)
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
    
        

       



    
    


    

    



    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
