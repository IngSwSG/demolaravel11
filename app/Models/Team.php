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

        $this->guardAgainstTooManyMembers();

        if ($users instanceof User) {
            $users = collect([$users]);

        }
        $currentCount = $this->users()->count();
        $additionalCount = $users->count();
    
        if ($currentCount + $additionalCount > $this->size) {
            throw new Exception('No se pueden añadir mas miembros.');
        }
    
        $this->users()->saveMany($users);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected function guardAgainstTooManyMembers()
    {
        if ($this->users()->count() >= $this->size) {
            throw new Exception();
        }
    }
}
