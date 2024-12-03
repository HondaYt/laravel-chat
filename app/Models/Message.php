<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    /**
     * 暗号化する属性
     *
     * @var array
     */
    protected $casts = [
        'message' => 'encrypted'
    ];

    /**
     * 代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
