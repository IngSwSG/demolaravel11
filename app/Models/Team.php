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
        // Verificar si $users es una colección o un array
        if ($users instanceof \Illuminate\Support\Collection || is_array($users)) {
            foreach ($users as $user) {
                $this->guardAgainstTooManyMembers();
                $this->users()->save($user);
            }
        } else {
            // Verificar un solo usuario
            $this->guardAgainstTooManyMembers();
            return $this->users()->save($users);
        }
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected function guardAgainstTooManyMembers()
    {
        if ($this->users()->count() >= $this->size) {
            throw new Exception('Limite excedido.');
        }
    }
}
