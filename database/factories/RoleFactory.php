<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Role;
use Faker\Generator as Faker;


$factory->define(Role::class, static function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->text(10)
    ];
});

$factory->state(Role::class, 'admin', ['name' => 'admin']);
$factory->state(Role::class, 'manager', ['name' => 'manager']);
$factory->state(Role::class, 'user', ['name' => 'user']);

$factory->afterCreatingState(Role::class, 'admin', static function(Role $role) {
    $role->abilities()->save(
        factory(App\Ability::class)->state('create')->make()
    );
    $role->abilities()->save(
        factory(App\Ability::class)->state('edit')->make()
    );
    $role->abilities()->save(
        factory(App\Ability::class)->state('read')->make()
    );
    $role->abilities()->save(
        factory(App\Ability::class)->state('delete')->make()
    );
});

$factory->afterCreatingState(Role::class, 'manager', static function(Role $role) {
    $role->abilities()->save(
        factory(App\Ability::class)->state('edit')->make()
    );
    $role->abilities()->save(
        factory(App\Ability::class)->state('read')->make()
    );
});

$factory->afterCreatingState(Role::class, 'user', static function(Role $role) {
    $role->abilities()->save(
        factory(App\Ability::class)->state('read')->make()
    );
});
