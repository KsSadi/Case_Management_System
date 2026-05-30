<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->date('next_date')->nullable()->change();
            $table->date('nispotti_date')->nullable()->after('is_nispotti');
        });
    }

    public function down()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->dropColumn('nispotti_date');
            $table->date('next_date')->nullable(false)->change();
        });
    }
};
