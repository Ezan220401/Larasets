<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_categorys', function (Blueprint $table) {
            $table->bigIncrements('category_id');
            $table->string('category_name');
            $table->text('category_desc');
            $table->string('approvals');
            $table->string('for_one_position');
            $table->string('for_one_name');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('loan_categorys');
    }
};
