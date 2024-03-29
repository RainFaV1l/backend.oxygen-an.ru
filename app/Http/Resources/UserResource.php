<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => auth('api')->user()->id,
            'email' => auth('api')->user()->email,
            'full_name' => auth('api')->user()->full_name,
            'role_id' => auth('api')->user()->role_id,
            'telephone' => auth('api')->user()->telephone,
        ];
    }
}
