<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('role')->default('user');

            $table->string('username')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile')->default('img_avatar.png');
            $table->text('fb_client')->nullable();
            $table->text('fb_secret')->nullable();
            $table->text('fb_token')->nullable();
            $table->bigInteger('fb_page')->nullable();
            $table->bigInteger('fb_account')->nullable();

            $table->text('gg_client')->nullable();
            $table->text('gg_secret')->nullable();
            $table->text('gg_dev')->nullable();
            $table->text('gg_manager')->nullable();
            $table->text('gg_customer')->nullable();
            $table->text('gg_access')->nullable();
            $table->text('gg_refresh')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
