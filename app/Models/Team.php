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
            // return $this->users()->save($users);
            $users = collect([$users]);
        }

        // $this->guardAgainstTooManyMembers();
        $this->guardAgainstTooManyMembers($users->count());
        return $this->users()->saveMany($users);


    
        // $this->users()->saveMany($users);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected function guardAgainstTooManyMembers($newUsersCount)
    {

        $currentCount = $this->users()->count();
        $totalUsers = $currentCount + $newUsersCount;

        if ($totalUsers > $this->size) {
            throw new Exception();
        }
    }
}
