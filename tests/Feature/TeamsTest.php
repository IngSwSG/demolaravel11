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


// Actividad 10 Prueba regresión

it('prueba de regresion teams', function() {
    
    // Arrange: Crea un equipo con tamaño máximo de 2
    $team = Team::factory()->create(['size' => 2]);
    $users = User::factory(3)->create(); //inserta 3 usuarios

    // Act: Llama al método add con múltiples usuarios
    $this->expectException(Exception::class);
    $team->add($users);

    // Assert: determina que solo se agregaron 2 usuarios
    expect($team->users()->count())->toBe(2);

    // un equipo puede agregar usuarios
    $team = Team::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $team->add($user1);
    $team->add($user2);

    expect($team->users()->count())->toBe(2);

    // un tamaño máximo
    $team = Team::factory()->create(['size' => 2]);
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $team->add($user1);
    $team->add($user2);

    expect($team->users()->count())->toBe(2);

    $this->expectException(Exception::class);
    $user3 = User::factory()->create();
    $team->add($user3);

    // un equipo puede agregar múltiples usuarios a la vez
    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(3)->create();

    $team->add($users);

    expect($team->users()->count())->toBe(3);

    // Intenta agregar más de 3 usuarios a un equipo con tamaño máximo 3
    $extraUsers = User::factory(4)->create();
    $this->expectException(Exception::class);
    $team->add($extraUsers);

    // Verifica que solo se agregaron 3 usuarios
    expect($team->users()->count())->toBe(3);
});
//Fin prueba