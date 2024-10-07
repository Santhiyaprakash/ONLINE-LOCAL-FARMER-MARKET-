<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function providerlogin()
    {
        return view("login_provider");
    }
    public function providerloginpost(Request $request)
    {
        $uname = $request->input('uname');
        $pass = $request->input('pass');

        // Run a raw SQL query to check if the username and password match
        $user = DB::select("SELECT * FROM ar_provider WHERE uname = ? AND pass = ? AND status =1", [$uname, $pass]);

        if (!empty($user)) {
            // Authentication successful
            Session::put('uname', $uname);

            return response()->json(['success' => true]);
        } else {
            // Authentication failed
            return response()->json(['success' => false]);
        }
    }
    
    public function providerregister()
    {
        return view("register_provider");
    }
    
    public function registerpro(Request $request)
    {
        
        $name = $request->input('name');
        $address = $request->input('address');
        $district = $request->input('district');
        $mobile = $request->input('mobile');
        $email = $request->input('email');
        $uname= $request->input('uname');
        $pass = $request->input('pass');
       
        $maxid = DB::table('ar_provider')->max('id') + 1;
        $maxid = $maxid ?? 1;
        
        $now = now();
        $create_date = $now->format('d-m-Y');

        $regpro=DB::table('ar_provider')->insert([
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

        if ($regpro) {
           

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    public function providerhome()
    {
        return view("providerhome");
    } 
    
    public function providerprofile($uname)
    {
        $providers = DB::table('ar_provider')->where('uname', $uname)->first();
        
        if ($providers) {
            return response()->json($providers);
        } else {
            return response()->json(['error' => 'Provider not found'], 404);
        }
    }
    
    public function addvehicle()
    {
        return view("addvehicle");
    }
   
    public function addvehiclepost(Request $request)
    {
        
        $uname = $request->input('uname');
        $vehicle = $request->input('vehicle');
        $vno = $request->input('vno');
        $details = $request->input('details');
        $cost1 = $request->input('cost1');
        $cost2= $request->input('cost2');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $folderPath = public_path('/uploads');
            $file->move($folderPath, $fileName);
            
        
       
        $maxid = DB::table('ar_vehicle')->max('id') + 1;
        $maxid = $maxid ?? 1;
        $now = now();
        $create_date = $now->format('d-m-Y');
        $vegpro=DB::table('ar_vehicle')->insert([
            'id' => $maxid,
            'uname' => $uname,
            'vehicle' => $vehicle,
            'vno' => $vno,
            'details' => $details,
            'cost1' => $cost1,
            'cost2' => $cost2,
            'photo' => $fileName,
            'create_date' => $create_date,
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
    
    public function vehicleinfo()
    {
        return view("vehicleinfo");
    }
    
    public function applyservices()
    {
        return view("applyservices");
    }

    
    
    /* public function vehicles($uname)
    {
        $vehicles = DB::table('ar_vehicle')->where('uname', $uname)->get();
        
        if ($vehicles->isNotEmpty()) {
            return response()->json(['data' => $vehicles]);
        } else {
            return response()->json(['error' => 'Provider not found'], 404);
        }
    } */ 
   /*  2public function vehicles($uname)
{
    $vehicles = DB::select('SELECT * FROM ar_vehicle WHERE uname = ?', [$uname]);

    if (!empty($vehicles)) {
        $vids = []; 

        foreach ($vehicles as $vehicle) {
            $vids[] = $vehicle->id; 
        }

        $bookingDetails = DB::table('ar_booking')
            ->select(
                'id',
                'vid',
                'provider',
                'duration',
                'time_type',
                'req_date',
                'status',
                'amount',
            )
            ->where('provider', $uname)
            ->whereIn('vid', $vids) 
            ->whereIn('status', [0, 1])
            ->get();

        $count = $bookingDetails->count();

        return response()->json([
            'vehicles' => $vehicles,
            'vehicle_booking' => $bookingDetails,
            'count' => $count,
        ]);
    } else {
        return response()->json([
            'error' => 'No vehicles found for the user',
        ], 404);
    }
}
 */

public function vehicles($uname)
{
    $vehicles = DB::table('ar_vehicle')
        ->select('ar_vehicle.*', 'ar_booking.duration', 'ar_booking.time_type', 'ar_booking.req_date', 'ar_booking.status', 'ar_booking.amount')
        ->join('ar_booking', 'ar_vehicle.id', '=', 'ar_booking.vid')
        ->where('ar_vehicle.uname', $uname)
        ->whereIn('ar_booking.status', [0, 1])
        ->get();

    if ($vehicles->isNotEmpty()) {
        return response()->json([
            'vehicles' => $vehicles,
            'count' => $vehicles->count(),
        ]);
    } else {
        return response()->json([
            'error' => 'No vehicles found for the user',
        ], 404);
    }
}
public function pro_request()
    {
        return view("pro_request");
    }
    
 /* public function requests($id)
{
    $requests = DB::table('ar_booking')
    ->select(
        'id',
        'uname',
        'provider',
        'vid',
        'duration',
        'time_type',
        'req_date',
        'status',
        'amount',
        'pay_st',
        'created_at',
        'updated_at'
    )
    ->where('id', $id)
    ->get();
    
    if ($requests) { */
/*         $vid = $requests->vid;
 */        
        // Update the 'ar_booking' table
       /*  DB::table('ar_booking')
            ->where('id', $bid)
            ->update(['status' => 1]);
        
        // Update the 'ar_vehicle' table
        DB::table('ar_vehicle')
            ->where('id', $vid)
            ->update(['status' => 1]);
        $providers = DB::table('ar_provider')->where('uname', $uname)->get();

 */



/* return response()->json(['data' => $requests]);


    } else {
        return response()->json(['error' => 'not found'], 404);
    }
} */
public function requests($id)
{
    $requests = DB::table('ar_booking')
        ->select(
            'ar_booking.id',
            'ar_booking.uname',
            'ar_booking.provider',
            'ar_booking.vid',
            'ar_booking.duration',
            'ar_booking.time_type',
            'ar_booking.req_date',
            'ar_booking.status',
            'ar_booking.amount',
            'ar_booking.pay_st',
            'ar_booking.created_at',
            'ar_booking.updated_at',
            'ar_vehicle.id',
            'ar_vehicle.vehicle',
            'ar_vehicle.vno',
            'ar_vehicle.details',
            'ar_vehicle.cost1',
            'ar_vehicle.create_date',
            'ar_vehicle.cost1',
            'ar_vehicle.cost2',
            'ar_vehicle.photo',
            'ar_user.address',
            'ar_user.mobile',


        )
        ->join('ar_vehicle', 'ar_booking.vid', '=', 'ar_vehicle.id')
        ->join('ar_user', 'ar_user.uname', '=', 'ar_booking.uname')

        ->where('ar_booking.id', $id)
        ->first();

    if ($requests) {
        return response()->json(['data' => $requests]);
    } else {
        return response()->json(['error' => 'not found'], 404);
    }
}
public function proRequest(Request $request)
{
    $bid = $request->input('bid');
    $vid = $request->input('vid');
    DB::table('ar_booking')
            ->where('id', $bid)
            ->update(['status' => 1]);
        
        DB::table('ar_vehicle')
            ->where('id', $vid)
            ->update(['status' => 1]);


    return response()->json(['message' => 'vehicle rent approved']);
}

public function pro_history()
{
    return view("pro_history");
}

    public function showservice()
    {
        $providers = DB::table('ar_service')->get();
        return response()->json(['data' => $providers]);
    }
    
    public function applyser()
    {
        return view("applyser");
    }
    
    public function applyservicepost(Request $request)
    {
        

        $serviceid = $request->input('serviceid');
        $service = $request->input('service');
        $provider = $request->input('provider');
        $address = $request->input('address');
        $city = $request->input('city');
        $mobile= $request->input('mobile');
        $maxid = DB::table('ar_apply_service')->max('id') + 1;
        $maxid = $maxid ?? 1;
        $now = now();
        $create_date = $now->format('d-m-Y');
        $vegpro=DB::table('ar_apply_service')->insert([
            'id' => $maxid,
            'serviceid' => $serviceid,
            'service' => $service,
            'provider' => $provider,
            'address' => $address,
            'city' => $city,
            'mobile' => $mobile,
            'status' => '0',
            'rdate' => $create_date,

            


        ]);

        if ($vegpro) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    
    }
    
    public function proservicestatus()
    {
        return view("proservicestatus");
    }
      
    public function showmyservice($storedUname)
    {
        $booking = DB::table('ar_apply_service')
            ->where('provider', $storedUname)
            ->get();
    
        if ($booking->isNotEmpty()) {
            return response()->json([
                'data' => $booking,
               
            ]);
        } else {
            return response()->json([
                'error' => 'No booking found for the Farmer',
            ], 404);
        }
    }
   
    public function deleteservice($id)
    {
        try {
            DB::table('ar_apply_service')->where('id', $id)->delete();

            return response()->json(['message' => 'Service deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting service'], 500);
        }
    }
    public function serviceinfo()
    {
        return view("serviceinfo");
    }
          
public function myagriservices($uname)
{
    $products = DB::table('ar_service_booking')
    ->select('ar_service_booking.*', 'ar_customer.address', 'ar_customer.mobile', 'ar_customer.email', 'ar_apply_service.serviceid', 'ar_service_booking.sid', 'ar_apply_service.service')
    ->join('ar_customer', 'ar_service_booking.uname', '=', 'ar_customer.uname')
    ->join('ar_apply_service', 'ar_apply_service.serviceid', '=', 'ar_service_booking.sid') 
    ->where('ar_service_booking.provider', $uname)
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

public function genbill($id)
{
    $bookings = DB::table('ar_service_booking')->where('id', $id)->first();
    
    if ($bookings) {
/*         DB::table('ar_service_booking')->where('id', $id)->update(['status' => 1]);
 */        
        return response()->json($bookings);
    } else {
        return response()->json(['error' => 'Booking not found'], 404);
    }
}

    public function servicegenbill()
    {
        return view("servicegenbill");
    }
    
    public function genratebillfarmer(Request $request)
{
    $id = $request->input('id');
    $amount = $request->input('amount');

    $validator = Validator::make($request->all(), [
        'id' => 'required',
        'amount' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $bookings = DB::table('ar_service_booking')->where('id', $id)->first();

    if ($bookings) {
        DB::table('ar_service_booking')->where('id', $id)->update(['status' => 1, 'amount' => $amount]);

        return response()->json(['success' => 'Bill generated successfully', 'booking' => $bookings]);
    } else {
        return response()->json(['error' => 'Booking not found or could not generate bill'], 404);
    }
}

public function farmerserviceinfo()
{
    return view("serviceinfo1");
}
      
public function myfaragriservices($uname)
{
    $products = DB::table('farmer_service_booking')
    ->select('farmer_service_booking.*', 'ar_user.address', 'ar_user.mobile', 'ar_user.email', 'ar_apply_service.serviceid', 'farmer_service_booking.sid', 'ar_apply_service.service')
    ->join('ar_user', 'farmer_service_booking.uname', '=', 'ar_user.uname')
    ->join('ar_apply_service', 'ar_apply_service.serviceid', '=', 'farmer_service_booking.sid') 
    ->where('farmer_service_booking.provider', $uname)
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

    public function farmenserbillgen()
    {
        return view("farmenserbillgen");
    }
    
public function fargenbill($id)
{
    $bookings = DB::table('farmer_service_booking')->where('id', $id)->first();
    
    if ($bookings) {
/*         DB::table('ar_service_booking')->where('id', $id)->update(['status' => 1]);
 */        
        return response()->json($bookings);
    } else {
        return response()->json(['error' => 'Booking not found'], 404);
    }
}

    public function fargenratebill(Request $request)
{
    $id = $request->input('id');
    $amount = $request->input('amount');

    $validator = Validator::make($request->all(), [
        'id' => 'required',
        'amount' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $bookings = DB::table('farmer_service_booking')->where('id', $id)->first();

    if ($bookings) {
        DB::table('farmer_service_booking')->where('id', $id)->update(['status' => 1, 'amount' => $amount]);

        return response()->json(['success' => 'Bill generated successfully', 'booking' => $bookings]);
    } else {
        return response()->json(['error' => 'Booking not found or could not generate bill'], 404);
    }
}

    public function addlivestocks()
    {
        return view("addlivestocks");
    }
   
        public function addlivestockpost(Request $request)
        {
            
            $uname = $request->input('uname');
            $animal = $request->input('animal');
            $aweight = $request->input('aweight');
            $location = $request->input('location');
            $details = $request->input('details');
            $cost= $request->input('cost');
            $latitude= $request->input('latitude');
            $longitude= $request->input('longitude');
           
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $folderPath = public_path('/uploads/livestocks');
                $file->move($folderPath, $fileName);
                
            
           
            $maxid = DB::table('ar_live_stock_booking')->max('id') + 1;
            $maxid = $maxid ?? 1;
            $now = now();
            $create_date = $now->format('d-m-Y');
            $vegpro=DB::table('ar_live_stock_booking')->insert([
                'id' => $maxid,
                'provider' => $uname,
                'animal' => $animal,
                'aweight' => $aweight,
                'location' => $location,
                'details' => $details,
                'cost' => $cost,
                'file' => $fileName,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'rdate' => $create_date,
                
                
    
    
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
        
    public function allstocks(Request $request)
    {
        $providers = DB::table('ar_live_stock_booking')
        ->select('ar_live_stock_booking.*', 'ar_provider.address', 'ar_provider.mobile', 'ar_provider.uname')
        ->join('ar_provider', 'ar_live_stock_booking.provider', '=', 'ar_provider.uname') 
        ->get();
        return response()->json(['data' => $providers]);
    }
    
    public function proviewlivestocks()
    {
        return view("proviewlivestocks");
    }
    
    public function myvehicles(Request $request, $uname)
    {
        $providers = DB::table('ar_vehicle')
        ->where('status', 0)
        ->where('uname', $uname)
        ->get();

        return response()->json(['data' => $providers]);
    }

    public function deleteVechicle($id)
    {
        try {
            DB::table('ar_vehicle')->where('id', $id)->delete();

            return response()->json(['success' => true, 'message' => 'Vehicle deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete Vehicle']);
        }
    }

    
  
    public function updatevehicle(Request $request)
    {
        $id = $request->query('id');
        $vehicle = $request->query('vehicle');


        return view('updatevehicle', ['id' => $id, 'vehicle' => $vehicle]);
    }

    public function updatevehiclepost(Request $request)
{
    try {
        $id = $request->input('id');
        $vno = $request->input('vno');
        $details = $request->input('details');
        $cost1 = $request->input('cost1');
        $cost2 = $request->input('cost2');
       

        // Check if the file is present
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $folderPath = public_path('/uploads');
            $file->move($folderPath, $fileName);

            Log::info('Product Update Request Data:', [
                'id' => $id,
                'vno' => $vno,
                'details' => $details,
                'cost1' => $cost1,
                'cost2' => $cost2,
                'photo' => $fileName,
            ]);

            $vegpro = DB::table('ar_vehicle')
                ->where('id', $id)
                ->update([
                    'vno' => $vno,
                    'details' => $details,
                    'cost1' => $cost1,
                    'cost2' => $cost2,
                    'photo' => $fileName,
                ]);

            if ($vegpro) {
                // Success case
                Session::flash('success', 'Vehicle updated successfully');
            } else {
                // Failure case
                Session::flash('error', 'Failed to update Vehicle');
            }
        } else {
            // Handle the case where the file is not provided
            Session::flash('error', 'File not provided');
        }

        return redirect()->route('updatevehicle'); // Assuming you have a route named 'updateproduct'
    } catch (\Exception $e) {
        Log::error("An error occurred: " . $e->getMessage());
        Session::flash('error', 'An error occurred');
        return redirect()->route('updatevehicle');
    }
}

public function paidvehicles($uname)
{
    try {
        $vehicles = DB::table('ar_vehicle')
            ->select('ar_vehicle.*', 'ar_booking.duration', 'ar_booking.time_type', 'ar_booking.req_date', 'ar_booking.status', 'ar_booking.amount')
            ->join('ar_booking', 'ar_vehicle.id', '=', 'ar_booking.vid')
            ->where('ar_vehicle.uname', $uname)
            ->where('ar_booking.status', 2)
            ->get();

        if ($vehicles->isNotEmpty()) {
            return response()->json([
                'vehicles' => $vehicles,
                'count' => $vehicles->count(),
            ], 200); 
        } else {
            return response()->json([
                'error' => 'No vehicles found for the user',
            ], 404); 
        }
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'An error occurred while fetching paid vehicles',
        ], 500); 
    }
}


    public function cuslivestocks()
    {
        return view("cuslivestocks");
    }

    public function farliveStocks()
    {
        return view("farliveStocks");
    }

    public function farbooklivestocks($uname)
  {
    $livestocks = DB::table('ar_users_live_stock_booking')
    ->select('ar_users_live_stock_booking.*', 'ar_user.address', 'ar_user.mobile', 'ar_user.email', 'ar_live_stock_booking.id', 'ar_users_live_stock_booking.livestockid', 'ar_live_stock_booking.animal')
    ->join('ar_user', 'ar_users_live_stock_booking.uname', '=', 'ar_user.uname')
    ->join('ar_live_stock_booking', 'ar_live_stock_booking.id', '=', 'ar_users_live_stock_booking.livestockid') 
    ->where('ar_users_live_stock_booking.provider', $uname)
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
  public function cusbooklivestocks($uname)
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
            ->where('ar_users_live_stock_booking.provider', $uname)
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



      
  


   


    


   
}
