<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    use HasFactory;

    protected $table = "feedbackvoting";
    protected $fillable = ['feedback_id', 'vote', 'user_id'];
}
