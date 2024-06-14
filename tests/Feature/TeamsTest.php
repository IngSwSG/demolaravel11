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


it('no permite agregar más usuarios que el tamaño máximo', function() {
    $team = Team::factory()->create(['size' => 2]);
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    $team->add($user1);
    $team->add($user2);

    expect($team->users()->count())->toBe(2);

    // Este debe lanzar una excepción ya que el equipo ha alcanzado su tamaño máximo
    $this->expectException(Exception::class);
    $team->add($user3);
});

//esta prueba si paso, por lo cual no se toma en cuenta
it('Prueba de regresion permite agregar usuarios hasta el tamaño máximo', function() {
    $team = Team::factory()->create(['size' => 3]);
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    $team->add($user1);
    $team->add($user2);
    $team->add($user3);

    expect($team->users()->count())->toBe(3);
});

//esta prueba si paso, por lo cual no se toma en cuenta
it('Prueba de regresion maneja agregar múltiples usuarios a la vez respetando el tamaño máximo', function() {
    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(3)->create();

    $team->add($users);

    expect($team->users()->count())->toBe(3);
});


//esta es la prueba que falló al realizarlo para detectar el error, se cambio la funcion add y ahora si pasó
it('Prueba de regresion lanza una excepción al intentar agregar múltiples usuarios que exceden el tamaño máximo', function() {
    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(4)->create();

    $this->expectException(Exception::class);
    $team->add($users);
});

