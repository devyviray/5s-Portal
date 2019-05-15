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
            $table->integer('company_location');
            $table->string('category');
            $table->string('area');
            $table->integer('inspector');
            $table->date('date_of_inspection');
            $table->time('time_of_inspection');
            $table->integer('checklist_id');
            $table->integer('checklist_batch');
            $table->string('checklist_requirement');
            $table->string('checklist_description', 1000);
            $table->string('comment', 1000);
            $table->integer('points');
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
