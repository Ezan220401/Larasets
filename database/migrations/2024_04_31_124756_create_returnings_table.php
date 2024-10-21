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
        Schema::create('returnings', function (Blueprint $table) {
            $table->bigIncrements('return_id');
            $table->string('person_name');
            // $table->string('person_number_id');
            $table->integer('person_position');
            $table->unsignedBigInteger('document_number');            
            $table->string('asset_name');
            $table->integer('asset_quantity');                
            $table->string('witness_name');
            $table->integer('witness_position');
            $table->dateTime('return_date');
            $table->text('return_desc');
            $table->string('created_by');
            $table->string('evidence');
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
        Schema::dropIfExists('returnings');
    }
};
