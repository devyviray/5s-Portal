<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuHeadToReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->integer('department_id');
            $table->string('accompanied_by');
            $table->integer('department_head_id');
            $table->integer('cluster_head_id');
            $table->integer('group_president_id');
            $table->integer('is_draft')->nullable();
            $table->dateTime('date_submitted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->dropColumn('accompanied_by');
            $table->dropColumn('department_head_id');
            $table->dropColumn('cluster_head_id');
            $table->dropColumn('group_president_id');
            $table->dropColumn('is_draft');
            $table->dropColumn('date_submitted');
        });
    }
}
