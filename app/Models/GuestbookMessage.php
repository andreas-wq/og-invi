<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestbookMessage extends Model
{
    protected $fillable = ['name', 'message', 'attendance'];
}

