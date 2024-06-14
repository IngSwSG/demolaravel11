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
    
        $currentCount = $this->users()->count();
        $additionalCount = $users->count();
    
        // Verificar si agregar todos los usuarios excede el tamaño máximo
        if ($currentCount + $additionalCount > $this->size) {
            throw new Exception('El equipo ha alcanzado su tamaño máximo.');
        }
    
        // Iterar sobre cada usuario y guardar uno por uno
        foreach ($users as $user) {
            $this->users()->save($user);
        }
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
