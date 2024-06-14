<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    //El método add original no está manejando correctamente,
    //la adición de múltiples usuarios con respecto al tamaño máximo del equipo
    public function add($users)// se cambia esta funcion,el  calculo de cantidades, validacion de tamaño, guardar los usuarios
{
    if ($users instanceof User) {
        $users = collect([$users]);
    }

    $currentCount = $this->users()->count();
    $additionalCount = $users->count();

    if ($currentCount + $additionalCount > $this->size) {
        throw new Exception('ERROR, ha alcanzado su tamaño máximo.');
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
