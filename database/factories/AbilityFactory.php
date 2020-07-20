<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ability;
use Faker\Generator as Faker;

$factory->define(Ability::class, static function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->text(10)
    ];
});

$factory->state(Ability::class, 'create', ['name' => 'create']);
$factory->state(Ability::class, 'edit', ['name' => 'edit']);
$factory->state(Ability::class, 'read', ['name' => 'read']);
$factory->state(Ability::class, 'delete', ['name' => 'delete']);
