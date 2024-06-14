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

    $team->load('users');

    expect($team->users)->toHaveCount(3);
    expect($team->users()->count())->toBeLessThanOrEqual($team->size);
});


it('lanza una excepción si se excede el tamaño del equipo', function () {
    $team = Team::factory()->create(['size' => 2]);
    $users = User::factory(3)->create();

    try {
        $team->add($users);
    } catch (Exception $e) {
        $this->assertInstanceOf(Exception::class, $e);
        $this->assertEquals('El equipo ya tiene el número máximo de miembros.', $e->getMessage());
        return;
    }

    $this->fail('Se esperaba que se lanzara una excepción cuando se excede el tamaño del equipo.');
});
