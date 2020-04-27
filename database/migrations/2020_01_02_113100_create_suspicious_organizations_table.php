<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSuspiciousOrganizationsTable extends Migration
{
    public function up()
    {
        Schema::create('suspicious_organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('concatenated_name');
            $table->text('organization_name');
            $table->string('list_type')->nullable();
            $table->text('comment')->nullable();
            $table->text('address')->nullable();
            $table->text('alias')->nullable();
            $table->text('others')->nullable();
            $table->timestamps();
        });
       // \DB::statement("create index con_org_names_pg on suspicious_organizations using gin (concatenated_name gin_trgm_ops)");

    }

    public function down()
    {
        Schema::dropIfExists('suspicious_organizations');
    }
}
