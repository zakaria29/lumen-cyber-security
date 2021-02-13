<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Members extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("members", function(Blueprint $table){
            $table->string("member_id", 30)->primary();
            $table->string("member_name", 200);
            $table->string("team_id", 30)->index();
            $table->string("email")->unique();
            $table->string("username")->unique();
            $table->string("password", 500);
            $table->string("token", 500);
            $table->timestamps();

            $table->foreign("team_id")->references("team_id")->on("teams")
            ->onUpdate("cascade")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("members");
    }
}
