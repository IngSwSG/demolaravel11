<?php

namespace App\Models;


use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log; 

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['size'];

    public function add($users)
    {
        if ($users instanceof User) {
            $users = collect([$users]);
        }

        if ($users instanceof Collection) {
            $currentUsersCount = $this->users()->count();
            $newUsersCount = $users->count();
            $teamSize = $this->size;

          
            Log::debug("Current Users Count: {$currentUsersCount}");
            Log::debug("New Users Count: {$newUsersCount}");
            Log::debug("Team Size: {$teamSize}");

            if ($currentUsersCount + $newUsersCount > $teamSize) {
                throw new Exception('Team size limit reached.');
            }

            return $this->users()->saveMany($users);
        }

        throw new \InvalidArgumentException('Argument must be an instance of User or Collection.');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}