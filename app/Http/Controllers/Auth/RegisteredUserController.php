<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
   
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'lon' => ['required', 'numeric', 'between:-90,90'],
            'lat' => ['required', 'numeric', 'between:-180,180'],
            'image' => ['sometimes', 'image','mimes:jpeg,png,jpg,gif', 'max:2048'],
            'phone_number' => ['required','string','max:24'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users', 'unique:handy_man'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        if($request->hasFile('image')){
            $request->image = '/storage/'.$request->file('image')->store('/images/users','public');
            
        }else{
            $request['image'] ='/storage/images/users/illustration-of-human-icon-user-symbol-icon-modern-design-on-blank-background-free-vector.jpg';
        }

        $user = User::create(
            [
            'name' => $request->name,
            'email' => $request->email,
            'latitude' => $request->lat,
            'longitude' => $request->lon,
            'city' => $request->city,
            'profile_image' => $request->image,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]
    );


        return response()->json(["message"=>'account created','user'=>$user],201);
    }
}
