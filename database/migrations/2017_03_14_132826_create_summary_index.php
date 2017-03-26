<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSummaryIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->table('summary_hour', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time']);
        });
        Schema::connection('mongodb')->table('summary_day', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time']);
        });
        Schema::connection('mongodb')->table('summary_month', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time']);
        });
        Schema::connection('mongodb')->table('summary_year', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time']);
        });

        Schema::connection('mongodb')->table('summary_uv_hour', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time','visiter_id']);
        });
        Schema::connection('mongodb')->table('summary_uv_day', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time','visiter_id']);
        });
        Schema::connection('mongodb')->table('summary_uv_month', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time','visiter_id']);
        });
        Schema::connection('mongodb')->table('summary_uv_year', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time','visiter_id']);
        });

        Schema::connection('mongodb')->table('summary_browser_day', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time','browser']);
        });
        Schema::connection('mongodb')->table('summary_browser_month', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time','browser']);
        });
        Schema::connection('mongodb')->table('summary_browser_year', function (Blueprint $collection) {
            $collection->unique(['project_id','create_time','browser']);
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
