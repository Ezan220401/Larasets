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
        Schema::create('usings', function (Blueprint $table) {
            $table->bigIncrements('using_id');
            $table->unsignedBigInteger('document_number');
            $table->string('person_name');
            // $table->string('person_number_id');
            $table->integer('person_position');            
            $table->string('asset_name');
            $table->integer('asset_quantity');                
            $table->string('witness_name');
            $table->integer('witness_position');
            $table->dateTime('using_date');
            $table->text('using_desc');
            $table->string('created_by');
            $table->string('evidence');
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
};
