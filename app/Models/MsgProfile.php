<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsgProfile extends Model
{
    use HasFactory;
    protected $table = 'msg_profile'; 
    protected $fillable = [
        'full_name',
        'user_id',
        'email',
        'mobile_number',
        'gender',
        'dob',
    ];
}
