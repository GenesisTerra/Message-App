<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsgConversation extends Model
{
    use HasFactory;

    protected $table = 'msg_conversations'; // Table name

    protected $fillable = [
        'convo_id',
        'sender',
        'conversation',
        'filePath',
    ];
}
