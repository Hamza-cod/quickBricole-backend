<?php

namespace App\Http\Controllers;

use App\Http\Resources\HandymanCollection;
use App\Models\Handyman;
use App\Models\User;
use App\Policies\UserPolicy;
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

    public function quiqueHandyman(Request $request)
    {
        $user = $request->user();
        //    dd($user->name);
        // 1. Receive Client's Location
        $latitude = $user->latitude;
        $longitude = $user->longitude;

        // 2. Retrieve Worker Data and Calculate Distances
        $colesestHnadymansByCity = Handyman::selectRaw('*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [$latitude, $longitude, $latitude])
            ->where('city', $user->city)
            ->orderBy('distance')
            ->get();



        return response()->json(new HandymanCollection($colesestHnadymansByCity));
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
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'lon' => ['numeric', 'between:-90,90'],
            'lat' => ['numeric', 'between:-180,180'],
            'phone_number' => ['string', 'max:24'],
            'profile_image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable' ,'confirmed'],
        ]);
        if (isset($validated["password"]) && !empty($validated["password"])) {
            $validated["password"] = bcrypt($validated["password"]);
        }else {
            unset($validated["password"]);
        }
        
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = '/storage/'.$request->file('profile_image')->store('/images/users', 'public');
        } else {
            $validated['profile_image'] = $user->profile_image;
        }
        $user->update($validated);
        return response()->json(["message" => 'Account updated', 'user' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(["message" => "account deleted"]);
    }
}
