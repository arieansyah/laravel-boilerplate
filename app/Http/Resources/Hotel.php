<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Hotel extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($request);
        return [
            'status' => 'success',
            'message' => 'Hotel Dafam is successfully created',
            'data' => parent::toArray($request),
        ];
    }
}
