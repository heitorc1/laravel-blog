<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subtitle', 'body', 'user_id'];

    /***
     * Get user of post
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
