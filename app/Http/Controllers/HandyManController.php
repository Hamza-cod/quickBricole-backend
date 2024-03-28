<?php

namespace App\Http\Controllers;

use App\Http\Resources\HandymanCollection;
use App\Http\Resources\HandymanResource;
use App\Models\Handyman;
use App\Policies\HandymanPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HandymanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum,handyman'])->except('index','show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Handyman::with('category:id,name')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    //   public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show(Handyman $handyman)
    {
        // dd($handyman);
        return new HandymanResource($handyman);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Handyman $handyman)
    {
        $this->authorize($handyman);
        $validated = $request->validate([
             'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'lon' => [ 'numeric', 'between:-90,90'],
            'lat' => [ 'numeric', 'between:-180,180'],
            'image' => ['image','mimes:jpeg,png,jpg,gif', 'max:2048'],
            'phone_number' => ['string','max:24'],
            'description' => ['string', 'max:500'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'category' => ['numeric', 'exists:categories,id'],
            'password' => [ 'confirmed',],
        ]);
        $validated['password']= Hash::make($request->password);
         $handyman->fill($validated);
        if($request->hasFile('image')){
            $validated['image'] = '/storage/'.$request->file('image')->store('/images/handdymans','public');
        }else{
            $validated['image'] = $handyman->profile_image;
        }
        $handyman->save();
        return response()->json(["message"=>'account updated','user'=>$handyman]);
          
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Handyman $handyman)
    {
        $this->authorize($handyman);
            
        $handyman->delete();
        return response()->json(['message'=>'deleted']);
    }
    // check password
    public function checkHandymansPassword (Request $request,Handyman $handyman)
    {
        $request->validate(['oldpassword'=>'required']);
        if(Hash::check($request->oldpassword, $handyman->password))
        {
           return response()->json(["message"=>true]);
        }
        else{
             return response()->json(["message"=>'this not the old passowrd']);
        }
    }
}
