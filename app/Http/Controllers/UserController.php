<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
        $this->middleware(['auth:sanctum'])->except(['index','show']);
    }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,User $user)
    {
         $validated = $request->validate([
            'name' => [ 'string', 'max:255'],
            'city' => ['string', 'max:255'],
            'lon' => [ 'numeric', 'between:-90,90'],
            'lat' => ['numeric', 'between:-180,180'],
            'image' => ['sometimes', 'image','mimes:jpeg,png,jpg,gif', 'max:2048'],
            'email' => [ 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['confirmed', Rules\Password::defaults()],
        ]);
        // dd($request->image);
        if($request->hasFile('image')){
            $$validated->image = env('APP_URL').'/storage/'.$request->file('image')->store('/images/users','public');
        }
        $user->update($validated);
          return response()->json(["message"=>'account updated','user'=>$user],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(["message"=>"account deleted"]);
    }
}
