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
            $table->integer('operation_line_id');
            $table->integer('location_id');
            $table->integer('category_id');
            $table->integer('area_id');
            $table->integer('process_owner');
            $table->integer('inspector');
            $table->date('date_of_inspection');
            $table->string('time_of_inspection');
            $table->integer('checklist_id');
            $table->integer('checklist_batch');
            $table->string('name', 1000);
            $table->integer('points');
            $table->integer('ratings')->nullable();
            $table->integer('status');
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
