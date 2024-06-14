<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

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


it('un equipo puede agregar multiples usuarios a la vez (Prueba de regresion Solucionada)', function(){

    $this->expectException(Exception::class);
    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(4)->create();

    $team->add($users);

    expect($team->users)->count()->toBe(3);
});


