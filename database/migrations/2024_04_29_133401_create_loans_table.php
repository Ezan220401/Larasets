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
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('loan_id');
            $table->string('loan_name');
            $table->string('applicant_name');
            $table->bigInteger('applicant_phone');
            $table->string('applicant_position');
            $table->bigInteger('applicant_number_id');

            $table->string('loan_position');
            $table->string('loan_asset_name');
            $table->integer('loan_asset_quantity');
            $table->text('loan_desc');
            $table->dateTime('loan_date');
            $table->dateTime('loan_length');

            $table->string('is_academic_approve')->nullable();
            $table->string('is_student_approve')->nullable();
            $table->string('is_laboratory_approve')->nullable();
            $table->string('is_wr_approve')->nullable();
            $table->string('is_coordinator_approve')->nullable();

            $table->boolean('is_full_approve')->default(false);

            $table->text('loan_note_status');
            $table->boolean('is_reject')->default(false);
            
            $table->boolean('is_using')->default(false);
            $table->unsignedBigInteger('using_id')->nullable();

            $table->boolean('is_returned')->default(false);
            $table->unsignedBigInteger('return_id')->nullable();
            
            $table->string('created_by');
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
        Schema::dropIfExists('loans');
    }
};
