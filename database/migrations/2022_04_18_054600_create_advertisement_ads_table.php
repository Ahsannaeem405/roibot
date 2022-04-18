<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisement_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisements_id')->constrained('advertisements')->onDelete('cascade');
            $table->text('heading')->nullable();
            $table->text('body')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('pending');
            $table->integer('addSet_id')->nullable();
            $table->integer('addCreative_id')->nullable();
            $table->integer('add_id')->nullable();
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
        Schema::dropIfExists('advertisement_ads');
    }
}
