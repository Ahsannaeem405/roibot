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
            $table->text('button')->nullable();
            $table->text('url')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('pending');
            $table->text('addSet_id')->nullable();
            $table->text('addCreative_id')->nullable();
            $table->text('add_id')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('clicks')->default(0);
            $table->integer('impressions')->default(0);
            $table->text('cpc')->default(0);
            $table->integer('conversation')->default(0);
            $table->integer('total')->default(0);
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
