<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodnameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'FoodID' => $this->FoodID,
            'FoodDescription' => $this->FoodDescription,
            'FoodGroupName' => $this->foodgroup->FoodGroupName,
        ];
    }
}
