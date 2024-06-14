<?php

use App\Models\Team;
use App\Models\User;

it('un equipo puede agrear usuarios', function(){
    $team = Team::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $team->add($user1);
    $team->add($user2);

    expect($team->users()->count())->toBe(2);
});

it('un equipo puede tener un tamaño maximo', function(){
    $team = Team::factory()->create(['size' => 2]);
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $team->add($user1);
    $team->add($user2);

    expect($team->users()->count())->toBe(2);

    $this->expectException(Exception::class);
    $user3 = User::factory()->create();
    $team->add($user3);
});

it('un equipo puede agregar multiples usuarios a la vez', function(){
    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(3)->create();

    $team->add($users);

    expect($team->users()->count())->toBe(3);
});

// Prueba de regresion
it('un equipo puede agregar múltiples usuarios a la vez - regresion', function(){
    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(5)->create();

    expect(function() use ($team, $users) {
        $team->add($users);
    })->toThrow(Exception::class, 'El equipo no puede tener más miembros de los permitidos.');

    expect($team->users()->count())->toBe(0); // Verifica que no se agreguen usuarios si se lanza una excepción
});

