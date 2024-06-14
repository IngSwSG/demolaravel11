<?php

use App\Models\Team;
use App\Models\User;
use Exception;
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

it('un equipo puede agregar multiples usuarios a la vez', function () {
    $team = Team::factory()->create(['size' => 3]);
    $users = User::factory(3)->create();

    // Prueba agregar usuarios hasta el límite
    $team->add($users);
    expect($team->users()->count())->toBe(3); // Verificamos que haya 3 usuarios

    // Prueba de regresión: intenta agregar más usuarios de los permitidos
   /* $this->expectException(Exception::class);
    $moreUser = User::factory()->create();
    $team->add($moreUser);*/
});

it('Prueba de regresion lanza una excepción ', function() {

    $team = Team::factory()->create(['size' => 3]);

    // Creamos una colección de 4 usuarios
    $users = User::factory(4)->create();
    try {
        $team->add($users);
    } catch (Exception $e) {
        // Verificamos que se haya lanzado una excepción del tipo Exception
        $this->assertInstanceOf(Exception::class, $e);
        return;
    }

    $this->fail('Se esperaba una excepción al intentar agregar más usuarios de los permitidos.');
});


            
