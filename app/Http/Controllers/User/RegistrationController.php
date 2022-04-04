<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Carrier;
use App\Models\TrailerSize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RegistrationController extends Controller
{
    public function carrierCarInputs(Request $request)
    {
        if (isset($request->delivery_type)) {
            echo ' <div class="mb-2">Vehicle</div>
                   <select onchange="activateRegisterBtn()" id="vehicle" name="vehicle" class="input w-full border flex-1 your_car">';
            foreach (\App\Models\Vehicle::where('delivery_type_id', $request->delivery_type)->select('id', 'name_'.$request->lang)->get() as $vehicle) {
                echo '<option value="'.$vehicle->id.'">'.$vehicle['name_'.$request->lang].'</option>';
            }
            echo '</select>';
            echo "<script type='text/javascript'> $(document).ready(function () {
            $('#vehicle').change(function () {
                $('#vehicle_type_div').load('".route('input-function',$request->lang)."?vehicle=' + document.getElementById('vehicle').value);
                document.getElementById('weight_div').style.display = 'block';
                document.getElementById('height_div').style.display = 'block';
                document.getElementById('width_div').style.display = 'block';
                document.getElementById('length_div').style.display = 'block';
            });
        }) </script>";
        }elseif (isset($request->vehicle)){
            echo ' <div class="mb-2">Vehicle Types</div>
                   <select onchange="activateRegisterBtn()" name="vehicle_type" class="input w-full border flex-1 your_car">';
            foreach (\App\Models\VehicleType::where('vehicle_id', $request->vehicle)->select('id', 'name_'.$request->lang)->get() as $vehicle_type) {
                echo '<option value="'.$vehicle_type->id.'">'.$vehicle_type['name_'.$request->lang].'</option>';
            }
            echo '</select>';
        }

    }

    public function register(Request $request){
        $this->validate($request, [
            'role' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users',
            'phone_number' => 'required',
            'password' => 'required|min:8|same:confirm_password',
        ]);

        if ($request->role == 'carrier'){
            $this->validate($request, [
                'vehicle_number' => 'required',
                'who_are_you' => 'required',
                'vehicle_model' => 'required',
                'delivery_type' => 'required',
                'vehicle' => 'required',
                'vehicle_type' => 'required',
                'weight' => 'required',
                'height' => 'required',
                'width' => 'required',
                'length' => 'required',
            ]);

            $user = new User();

            $user->name = $request->first_name."_".$request->last_name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);

            if ($request->who_are_you == 'jur'){
                $this->validate($request, [
                    'company_name' => 'required',
                ]);
                $user->company_name = $request->company_name;
            }



            $carrier = new Carrier();

            $carrier->user_id = $user->id;
            $carrier->vehicle_model_id = $request->vehicle_model;
            $carrier->delivery_type_id = $request->delivery_type;
            $carrier->vehicle_type_id = $request->vehicle_type;
            $carrier->vehicle_number = $request->vehicle_number;

            $trailer_size = new TrailerSize();

            $trailer_size->weight = $request->weight;
            $trailer_size->height = $request->height;
            $trailer_size->width = $request->width;
            $trailer_size->length = $request->length;

            $carrier->trailer_size_id = $trailer_size->id;

            if ($user->save() && DB::table('role_user')->insert(array('user_id'=>$user->id,'role_id'=>4)) && DB::table('phone_number')->insert(array('user_id'=>$user->id,'phone_number'=>$request->phone_number)) && $carrier->save() && $trailer_size->save()) {
                Auth::login($user);
                return redirect()->route('home', $request->lang)->with(['success' => 'You Logged In Successfully!']);
            }


            }elseif ($request->role == 'declarant'){

        }
    }
}
