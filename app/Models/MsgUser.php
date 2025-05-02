<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsgUser extends Model
{
    use HasFactory;
    protected $table = 'MsgUsers';
    protected $fillable = [
        'user_id',
        'email',
        'password',
        'failed_attempts',
        'lock_until',
        'last_attempt'
    ];
    public $timestamps = false;
}
