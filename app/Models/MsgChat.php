<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsgChat extends Model
{
    use HasFactory;

    protected $table = 'msg_chats';

    protected $fillable = [
        'user_id_sender',
        'receiver',
        'user_id_receiver',
        'convo_id',
    ];
}
