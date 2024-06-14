<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    public function add($users)
{
    $this->guardAgainstTooManyMembers(is_array($users) ? count($users) : 1);

    if ($users instanceof User) {
        return $this->users()->save($users);
    }

    $this->users()->saveMany($users);
}

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected function guardAgainstTooManyMembers($additionalUsers = 0)
    {
        $totalUsers = $this->users()->count() + $additionalUsers;
        if ($totalUsers >= $this->size) {
            throw new Exception();
        }
    }
}
