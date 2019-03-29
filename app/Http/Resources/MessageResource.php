<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class MessageResource extends JsonResource
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
            'user'    => User::find($this->user_id)->name,
            'sent_at' => $this->created_at->toArray()["timestamp"],
            'content' => $this->content
        ];
    }
}
