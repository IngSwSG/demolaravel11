<?php

use App\Models\Team;
use App\Models\User;

it('un equipo puede agrear usuarios', function(){
    $team = Team::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $team->add($user1);
    $team->add($user2);

    expect($team->users)->count()->toBe(2);
});

it('un equipo puede tener un tamaño maximo', function(){
    $team = Team::factory()->create(['size' => 2]);
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $team->add($user1);
    $team->add($user2);

    expect($team->users)->count()->toBe(2);

    $this->expectException(Exception::class);
    $user3 = User::factory()->create();
    $team->add($user3);
});

it('un equipo puede agregar multiples usuarios a la vez', function(){
    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(3)->create();

    $team->add($users);

    expect($team->users)->count()->toBe(3);
});

// Nueva prueba de regresión, con el modelo original no debe pasar la prueba con el modelo corregido debe dar positivo
it('no permite agregar más usuarios de los permitidos al agregar múltiples usuarios a la vez', function() {
    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(4)->create(); // Se crean 4 usuarios, excediendo el tamaño del equipo

    $this->expectException(Exception::class);
    $team->add($users); // Esto debería lanzar una excepción.
});
