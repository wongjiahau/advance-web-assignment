<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nexmo\Message\Response\Message;

class Group extends Model
{
    //
    protected $fillable = [
        'name'
    ];

    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['is_admin']);
    }
}
