<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSummaryPvIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->table('summary_pv_hour', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time']);
        });
        Schema::connection('mongodb')->table('summary_pv_day', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time']);
        });
        Schema::connection('mongodb')->table('summary_pv_month', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time']);
        });
        Schema::connection('mongodb')->table('summary_pv_year', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time']);
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
