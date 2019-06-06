<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id');
            $table->integer('location_id');
            $table->integer('operation_line_id')->nullable();
            $table->integer('category_id');
            $table->integer('area_id');
            $table->integer('process_owner_id');
            $table->integer('inspector_id');
            $table->date('date_of_inspection');
            $table->string('time_of_inspection');
            $table->integer('ratings')->nullable();
            $table->integer('status');
            $table->date('reporting_month');
            $table->integer('validated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
