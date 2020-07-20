<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\TaskStatus;
use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, static function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'title' => $faker->text(85),
        'description' => $faker->text,
        'status' => TaskStatus::NEW()
    ];
});
