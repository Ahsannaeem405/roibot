<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditials', function (Blueprint $table) {
            $table->id();
            $table->text('facebook_app')->nullable();
            $table->text('facebook_secret')->nullable();
            $table->text('facebook_token')->nullable();

            $table->text('google_app')->nullable();
            $table->text('google_secret')->nullable();
            $table->text('google_token')->nullable();
            $table->text('google_refresh')->nullable();
            $table->text('manager')->nullable();
            $table->text('google_developer')->nullable();

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
        Schema::dropIfExists('creditials');
    }
}
