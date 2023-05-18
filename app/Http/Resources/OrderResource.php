<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = parent::toArray($request);
        
        return [
            'product_name' => $response['product_name'],
            'total_price' => $response['total_price'],
            'total_quantity' => $response['total_quantity']
        ];
    }
}
