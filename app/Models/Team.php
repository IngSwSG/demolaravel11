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

        $this->guardAgainstTooManyMembers(count($users));
        $this->users()->saveMany($users);

    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected function guardAgainstTooManyMembers($numberOfNewUsers = 0)
    {
        if ($this->users()->count() + $numberOfNewUsers > $this->size) {
            throw new Exception('No se pueden agregar mas miembros,limite excedido');
        }
    }
}
//