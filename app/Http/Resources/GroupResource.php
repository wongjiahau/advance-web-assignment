<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class GroupResource extends JsonResource
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
            'id'    => $this->id,
            'name'  => $this->name,
            'users' => $this->users->map(function ($x) {
                return [
                    'name' => $x->name,
                    'email'=> $x->email,
                    'rank' => $x->pivot->rank
                ];
            })
        ];
    }
}
