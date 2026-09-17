<?php

namespace App\Models;

use App\Models\AIMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIConversation extends Model
{
    use HasFactory;

    protected $table = 'ai_conversations';
    
    protected $fillable = [
        'title',
        'user_id',
    ];

    public function messages() {
        return $this->hasMany(AIMessage::class, 'conversation_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
