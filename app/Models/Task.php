<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'completed',
        'priority'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function path(){
        return '/tasks/'. $this->id;
    }

    public function complete()
    {
        $this->update(['completed' => true]);
    }
    public function incomplete()
    {
        $this->update(['completed' => false]);
    }
}
