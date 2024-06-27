<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function like()
    {
        $like = new Like();
        $like->user_id = auth()->id();
        $this->likes()->save($like);
    }

    public function unlike()
    {
        $this->likes()->where('user_id', auth()->id())->delete();
    }
}
