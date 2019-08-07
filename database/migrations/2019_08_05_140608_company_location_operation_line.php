<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyLocationOperationLine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_location_operation_line', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_location_id')->unsigned();
            $table->foreign('company_location_id')->references('id')->on('company_location')->onDelete('cascade');
            $table->bigInteger('operation_line_id')->unsigned();
            $table->foreign('operation_line_id')->references('id')->on('operation_lines')->onDelete('cascade');
            $table->index('company_location_id');
            $table->index('operation_line_id');
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
        //
    }
}
