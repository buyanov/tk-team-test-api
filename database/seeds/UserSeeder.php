<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->state('admin')->create();

        factory(App\User::class, 50)
            ->create()
            ->each( static function ($user) {
                $user->tasks()->createMany(
                    factory(App\Task::class, 3)->make()->toArray()
                );
            });
    }
}
