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
            $this->guardAgainstTooManyMembers(1); // Chequear por un solo usuario
            return $this->users()->save($users);
        }

        $this->guardAgainstTooManyMembers(count($users)); // Chequear por múltiples usuarios
        return $this->users()->saveMany($users);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected function guardAgainstTooManyMembers($count)
    {
        if ($this->users()->count() + $count > $this->size) {
            throw new Exception('Tamaño máximo del equipo alcanzado');
        }
    }
}
