<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisements_id')->constrained('advertisements')->onDelete('cascade');
            $table->text('data')->nullable();
            $table->text('data2')->nullable();
            $table->string('type')->nullable();
            $table->text('url')->nullable();
            $table->text('hash')->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('advertisement_details');
    }
}
