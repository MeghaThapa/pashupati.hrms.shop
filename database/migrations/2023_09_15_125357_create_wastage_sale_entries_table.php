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
        Schema::create('wastage_sale_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bill_id')->unsigned()->index();
            $table->foreign('bill_id')->references('id')->on('wastage_sales')->onDelete('cascade');
            $table->bigInteger('waste_id')->unsigned()->index();
            $table->foreign('waste_id')->references('id')->on('wastages_stock')->onDelete('cascade');
            $table->string('quantity');
            $table->enum("status",["sent","completed"])->default("sent");
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
        Schema::dropIfExists('wastage_sale_entries');
    }
};
