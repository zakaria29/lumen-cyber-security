<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Admins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("admins", function(Blueprint $table){
            $table->string("admin_id", 30)->primary();
            $table->string("admin_name");
            $table->string("username", 300);
            $table->string("password", 500);
            $table->string("token", 500);
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
        Schema::drop("admins");
    }
}
