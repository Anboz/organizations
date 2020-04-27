<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuspectsTable extends Migration
{

    public function up()
    {
        Schema::create('suspects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('concatenated_names');
            $table->string('second_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('fourth_name')->nullable();
            $table->string('organization')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('others')->nullable();
            $table->timestamps();
        });
       // \DB::statement("create index con_names_pg on suspects using gin (concatenated_names gin_trgm_ops)");

    }


    public function down()
    {
        Schema::dropIfExists('suspects');
    }
}
