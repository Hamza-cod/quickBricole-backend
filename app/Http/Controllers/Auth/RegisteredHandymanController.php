<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Handyman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredHandymanController extends Controller
{
   
     public function store(Request $request)
    {
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'lon' => ['required', 'numeric', 'between:-90,90'],
            'lat' => ['required', 'numeric', 'between:-180,180'],
            'image' => ['sometimes', 'image','mimes:jpeg,png,jpg,gif', 'max:2048'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:handy_man'],
            'phone_number' => ['required','string','max:24'],
            'description' => ['required','string', 'max:500'],
            'category' => ['required','numeric', 'exists:categories,id'],
            'password' => ['required', 'confirmed',],
        ]);

        if($request->hasFile('image')){
            $request->image = '/storage/'.$request->file('image')->store('/images/handymans','public');
            
        }else{
            $request['image'] = '/storage/images/users/user_default_image.png';
        }

        $bricoler = Handyman::create(
            [
            'name' => $request->name,
            'email' => $request->email,
            'latitude' => $request->lat,
            'longitude' => $request->lon,
            'city' => $request->city,
            'category_id' => $request->category,
            'profile_image' => $request->image,
            'phone_number' => $request->phone_number,
            'description' => $request->description,
            'password' => Hash::make($request->password),
        ]
    );
        return response()->json(["message"=>"account created seccessfully","bricoler"=>$bricoler],201);
    }
}
