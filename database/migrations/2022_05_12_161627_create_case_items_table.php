<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('case_items', function (Blueprint $table) {
            $table->id();
            $table->text('case_no');
            $table->string('division');
            $table->string('project');
            $table->string('case_type');
            $table->string('court_name');
            $table->string('parties_name');
            $table->text('case_details');
            $table->string('case_subject');
            $table->string('first_order')->nullable();
            $table->string('adv_name')->nullable();
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
        Schema::dropIfExists('case_items');
    }
}
