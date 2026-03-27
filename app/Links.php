<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $fillable = ['date', 'receiverId', 'senderId', 'description', 'link', 'deleted_at'];

    protected $table = 'links';
    public function sender()
    {
        return $this->belongsTo(User::class, 'senderId');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiverId');
    }

}
