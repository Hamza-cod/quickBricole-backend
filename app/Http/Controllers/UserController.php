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
        $this->middleware(['auth:sanctum']);
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
            'name' => [ 'required','string', 'max:255'],
            'city' => ['required','string', 'max:255'],
            'lon' => [ 'required','numeric', 'between:-90,90'],
            'lat' => ['required','numeric', 'between:-180,180'],
            'phone_number' => ['string','max:24'],
            'image' => ['sometimes', 'image','mimes:jpeg,png,jpg,gif', 'max:2048'],
            'email' => [ 'required','string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required','confirmed', Rules\Password::defaults()],
        ]);
        $validated['password']= Hash::make($request->password);
         $user->fill($validated);
        if($request->hasFile('image')){
            $validated['image'] = '/storage/'.$request->file('image')->store('/images/users','public');
        }else{
            $validated['image'] = $user->profile_image;
        }
        $user->save();
        return response()->json(["message"=>'account updated','user'=>$user]);
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
