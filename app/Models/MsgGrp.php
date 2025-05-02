<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsgGrp extends Model
{
    use HasFactory;

    protected $table = 'msg_grps'; 

    protected $fillable = [
        'grpId',
        'grp_name',
        'members',
        'convo_id',
    ];

    protected $casts = [
        'members' => 'array', 
    ];
}
