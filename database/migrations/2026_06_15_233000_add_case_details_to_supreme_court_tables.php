<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaseDetailsToSupremeCourtTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('high_court_cases', function (Blueprint $table) {
            $table->text('case_details')->nullable()->after('parties_name');
        });

        Schema::table('appellate_cases', function (Blueprint $table) {
            $table->text('case_details')->nullable()->after('parties_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('high_court_cases', function (Blueprint $table) {
            $table->dropColumn('case_details');
        });

        Schema::table('appellate_cases', function (Blueprint $table) {
            $table->dropColumn('case_details');
        });
    }
}
