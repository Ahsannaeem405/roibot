<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('goal')->nullable();
            $table->text('title')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('per_day')->nullable();
            $table->integer('type')->nullable();
            $table->string('status')->default('pending');
            $table->text('compain_id')->nullable();

            $table->text('cities')->nullable();
            $table->text('countries')->nullable();
            $table->text('interest')->nullable();
            $table->text('demo')->nullable();
            $table->text('behaviour')->nullable();

            $table->integer('step')->default(1);



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
        Schema::dropIfExists('advertisements');
    }
}
