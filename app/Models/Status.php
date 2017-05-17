<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['content']; //允许更新
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
