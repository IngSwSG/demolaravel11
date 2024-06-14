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

        $currentSize = $this->users()->count();
        $additionalUsers = is_array($users) ? count($users) : 1;
        $newSize = $currentSize + $additionalUsers;

        if ($newSize > $this->size) {
            throw new Exception("El equipo no puede tener más de {$this->size} usuarios.");
        }

        if (is_array($users)) {
            $this->users()->attach($users);
        } else {
            $this->users()->attach($users);}
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
