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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('url')->nullable();
            $table->string('action_btn')->nullable();
            $table->string('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('budget')->nullable();
            $table->string('per_day')->nullable();
            $table->string('duration')->nullable();
            $table->integer('type')->nullable();
            $table->string('status')->default('pending');
            $table->integer('compain_id')->nullable();



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
