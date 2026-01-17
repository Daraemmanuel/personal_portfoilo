<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'reaction_type',
        'ip_address',
        'user_agent',
    ];

    public function comment()
    {
        return $this->belongsTo(ArticleComment::class, 'comment_id');
    }
}
