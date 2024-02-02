<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'title', 'description', 'category', 'category', 'user_id', 'attachments'];
    protected $table = 'feedback';

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'DESC');
    }

    public function like()
    {
        return $this->hasMany(Voting::class, 'feedback_id')->where('vote', 1);
    }

    public function dislike()
    {
        return $this->hasMany(Voting::class, 'feedback_id')->where('vote', 2);
    }

    public function comment_data()
    {
        return $this->hasMany(Comment::class, 'feedback_id')->orderBy('id', 'DESC');
    }
}
