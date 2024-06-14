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
        if ($users instanceof User) {
            $users = collect([$users]);
        }

        $this->guardAgainstTooManyMembers($users->count());

        return $this->users()->saveMany($users);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected function guardAgainstTooManyMembers($newUsersCount)
    {
        if ($this->users()->count() + $newUsersCount > $this->size) {
            throw new Exception("Team size exceeded");
        }
    }
}
