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
        Schema::create('assets', function (Blueprint $table) {
            $table->bigIncrements('asset_id');
            $table->string('asset_name');
            $table->string('asset_code');
            $table->string('asset_type');
            $table->integer('receipt_number');
            $table->text('asset_desc');
            $table->text('maintenance_desc');
            $table->integer('asset_quantity');
            $table->string('asset_position');
            $table->dateTime('asset_date_of_entry');
            $table->integer('category_id');
            $table->integer('on_borrow')->default(0);
            $table->string('asset_image')->nullable();
            $table->integer('asset_price');
            $table->string('created_by')->default(1);
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
        Schema::dropIfExists('assets');
    }
};
