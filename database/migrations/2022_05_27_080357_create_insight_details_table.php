<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsightDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insight_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_id')->constrained('advertisement_ads')->onDelete('cascade');
            $table->integer('clicks')->default(0);
            $table->integer('impressions')->default(0);
            $table->text('cpc')->nullable();
            $table->timestamp('date')->nullable();
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
        Schema::dropIfExists('insight_details');
    }
}
