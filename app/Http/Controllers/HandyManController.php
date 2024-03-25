<?php

namespace App\Http\Controllers;

use App\Models\HandyMan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class HandyManController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'lon' => ['required', 'numeric', 'between:-90,90'],
            'lat' => ['required', 'numeric', 'between:-180,180'],
            'image' => ['sometimes', 'image','mimes:jpeg,png,jpg,gif', 'max:2048'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'category_id' => ['required', 'exists:categorys,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        if($request->hasFile('image')){
            $request->image = env('APP_URL').'/storage/'.$request->file('image')->store('/images/users','public');
            
        }else{
            $request['image'] = env('APP_URL').'/storage/images/users/user_default_image.png';
        }

        $handyMan = HandyMan::create(
            [
            'name' => $request->name,
            'email' => $request->email,
            'latitude' => $request->lat,
            'longitude' => $request->lon,
            'city' => $request->city,
            'profile_image' => $request->image,
            'password' => Hash::make($request->password),
        ]
    );

    }

    /**
     * Display the specified resource.
     */
    public function show(HandyMan $handyMan)
    {
        //
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HandyMan $handyMan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HandyMan $handyMan)
    {
        //
    }
}
