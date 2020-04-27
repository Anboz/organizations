<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('crm_client_id')->unique()->unsigned();
            $table->string('concatenated_names');
            $table->string('second_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('fourth_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('client_registration_date')->nullable();
            $table->text('other')->nullable();

            $table->timestamps();

        });
     //   DB::statement("create index crm_client_pg on crm_clients using gin (concatenated_names gin_trgm_ops)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_clients');
    }
}
