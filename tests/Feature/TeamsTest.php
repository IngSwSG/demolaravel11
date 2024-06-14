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

it('un equipo puede agregar múltiples usuarios a la vez', function(){
    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(3)->create();

    $team->add($users);

    // Refrescar el equipo para obtener la lista actualizada de usuarios
    $team->refresh();

    expect($team->users)->toHaveCount(3);
});


it('prueba de lanzar excepcion', function(){

    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(4)->create(); // Intentar agregar 4 usuarios a un equipo con tamaño 3

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Limite excedido.');

    $team->add($users);
});

