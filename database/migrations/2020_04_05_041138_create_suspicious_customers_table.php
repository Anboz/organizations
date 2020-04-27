<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuspiciousCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspicious_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('crm_client_id')->unsigned();
            $table->integer('suspects_id')->unsigned();
            $table->string('second_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('fourth_name')->nullable();
            $table->string('organization')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('client_registration_date')->nullable();
            $table->double('sim')->unsigned();
            $table->text('other')->nullable();

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
        Schema::dropIfExists('suspicious_customers');
    }
}
