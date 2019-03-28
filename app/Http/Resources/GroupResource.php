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
            'name'  => $this->name,
            'users' => $this->users->map(function ($x) {
                return [
                    'name'     => $x->name,
                    'email'    => $x->email,
                    'is_admin' => $x->pivot->group_id == $this->id && $x->pivot->is_admin == 1
                ];
            })
        ];
    }
}
