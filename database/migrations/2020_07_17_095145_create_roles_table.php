<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('title')->nullable();
            $table->timestamps();
        });

        Schema::create('abilities', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('title')->nullable();
            $table->timestamps();
        });

        Schema::create('ability_role', static function (Blueprint $table) {
            $table->primary(['role_id', 'ability_id']);
            $table->uuid('role_id');
            $table->uuid('ability_id');
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('ability_id')
                ->references('id')
                ->on('abilities')
                ->onDelete('cascade');
        });

        Schema::create('role_user', static function (Blueprint $table) {
            $table->primary(['user_id', 'role_id']);
            $table->uuid('user_id');
            $table->uuid('role_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('ability_role');
        Schema::dropIfExists('abilities');
        Schema::dropIfExists('roles');
    }
}
