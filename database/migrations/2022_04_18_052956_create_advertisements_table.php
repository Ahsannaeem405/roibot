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
            $table->text('dimentions')->nullable();
            $table->text('business')->nullable();
            $table->text('target')->nullable();
            $table->text('keywords')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('age')->nullable();
            $table->string('age2')->nullable();
            $table->string('gender')->nullable();
            $table->string('per_day')->nullable();
            $table->integer('type')->nullable();
            $table->string('status')->default('pending');
            $table->text('compain_id')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->text('cities')->nullable();
            $table->text('countries')->nullable();
            $table->text('interest')->nullable();
            $table->text('life_events')->nullable();
            $table->text('family_statuses')->nullable();
            $table->text('industries')->nullable();
            $table->text('income')->nullable();
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
