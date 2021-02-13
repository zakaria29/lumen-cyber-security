<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Teams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("teams", function(Blueprint $table){
            $table->string("team_id", 30)->primary();
            $table->string("team_name", 200);
            $table->string("school_id", 30)->index();
            $table->timestamps();
            $table->foreign("school_id")
            ->references("school_id")
            ->on("schools")
            ->onUpdate("cascade")
            ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("teams");
    }
}
