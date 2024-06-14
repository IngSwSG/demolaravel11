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
            $this->guardAgainstTooManyMembers(1);
            return $this->users()->save($users);
        }

        if ($users instanceof \Illuminate\Database\Eloquent\Collection ) {
            $this->guardAgainstTooManyMembers($users->count());
            return $this->users()->saveMany($users);

        }
    }


    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected function guardAgainstTooManyMembers($newMembersCount)
    {
        if (($this->users()->count() + $newMembersCount) > $this->size) {
            throw new Exception();
        }
    }
}
