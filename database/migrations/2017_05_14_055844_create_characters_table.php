<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->bigIncrements('auto_id');
            $table->uuid('id')->index();

            $table->string('name')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->enum('type', ['player', 'merchant', 'civilian', 'monster']);

            $table->unsignedInteger('xp')->default(0);
            $table->unsignedInteger('available_attribute_points')->default(0);
            $table->integer('reputation');

            // attributes
            $table->integer('strength');
            $table->integer('agility');
            $table->integer('constitution');
            $table->integer('intelligence');
            $table->integer('charisma');

            // stats
            $table->integer('hit_points');
            $table->integer('total_hit_points');

            // statistics
            $table->integer('battles_won')->default(0);
            $table->integer('battles_lost')->default(0);

            $table->unsignedInteger('level_id');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->uuid('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('restrict');

            $table->unsignedInteger('race_id');
            $table->foreign('race_id')->references('id')->on('races')->onDelete('restrict');

            $table->uuid('profile_picture_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characters');
    }
}
