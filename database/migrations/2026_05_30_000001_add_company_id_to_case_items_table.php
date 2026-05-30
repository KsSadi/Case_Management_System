<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToCaseItemsTable extends Migration
{
    public function up()
    {
        Schema::table('case_items', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('adv_name');
        });
    }

    public function down()
    {
        Schema::table('case_items', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
