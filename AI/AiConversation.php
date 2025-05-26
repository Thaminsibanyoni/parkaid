<?php

namespace App\Models\AI;

use Illuminate\Database\Eloquent\Model;

class AiConversation extends Model
{
    protected $table = 'ai_conversations';

    protected $fillable = [
        'user_id',
        'message',
        'is_user',
    ];
}
