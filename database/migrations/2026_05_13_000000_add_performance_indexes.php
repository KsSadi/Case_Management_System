<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add indexes to histories table for better query performance
        Schema::table('histories', function (Blueprint $table) {
            $table->index('case_id', 'idx_histories_case_id');
            $table->index('date', 'idx_histories_date');
            $table->index('next_date', 'idx_histories_next_date');
            $table->index(['date', 'case_id'], 'idx_histories_date_case');
        });

        // Add indexes to case_items table for better filtering
        Schema::table('case_items', function (Blueprint $table) {
            $table->index('division', 'idx_case_items_division');
            $table->index('case_type', 'idx_case_items_case_type');
            $table->index('court_name', 'idx_case_items_court_name');
            $table->index('project', 'idx_case_items_project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->dropIndex('idx_histories_case_id');
            $table->dropIndex('idx_histories_date');
            $table->dropIndex('idx_histories_next_date');
            $table->dropIndex('idx_histories_date_case');
        });

        Schema::table('case_items', function (Blueprint $table) {
            $table->dropIndex('idx_case_items_division');
            $table->dropIndex('idx_case_items_case_type');
            $table->dropIndex('idx_case_items_court_name');
            $table->dropIndex('idx_case_items_project');
        });
    }
}
