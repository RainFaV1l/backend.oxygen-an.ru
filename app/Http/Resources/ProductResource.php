<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    // Задание названия вместо data
    // public static $wrap = 'product';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return parent::toArray($request);

//        return [
//            'title' => $this->title
//            'meta' => $this->when($this->title === '8.61', function() { return 1; }, finction() { return 2; })
//        ];

    }
}
