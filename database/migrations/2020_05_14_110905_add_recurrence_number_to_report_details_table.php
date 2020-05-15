<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecurrenceNumberToReportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_details', function (Blueprint $table) {
            $table->integer('previous_rating');
            $table->integer('recurrence_number')->nullable();
            $table->string('criticality')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_details', function (Blueprint $table) {
            $table->dropColumn('previous_rating');
            $table->dropColumn('recurrence_number');
            $table->dropColumn('criticality');
        });
    }
}
