<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HandymanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "name"=>$this->name,
            "email"=> $this->email,
            "longitude"=> $this->longitude,
            "latitude"=>$this->latitude ,
            "city"=>$this->city,
            "profile_image"=> $this->profile_image,
            "category_id"=>$this->category_id ,
            "category"=>$this->category->name,
            "phone_number"=>$this->phone_number,
            "description"=>$this->description,
            "email_verified_at"=>$this->email_verified_at ,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
            "role" => $this->role,
        ];;
    }
}
