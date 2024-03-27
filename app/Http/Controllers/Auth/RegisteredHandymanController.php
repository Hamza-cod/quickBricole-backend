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
            'category' => ['required','numeric', 'exists:categories,id'],
            'password' => ['required', 'confirmed',],
        ]);

        if($request->hasFile('image')){
            $request->image = env('APP_URL').'/storage/'.$request->file('image')->store('/images/users','public');
            
        }else{
            $request['image'] = env('APP_URL').'/storage/images/users/user_default_image.png';
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
            'password' => Hash::make($request->password),
        ]
    );
        return response()->json(["message"=>"account created seccessfully","bricoler"=>$bricoler],201);
    }
}
