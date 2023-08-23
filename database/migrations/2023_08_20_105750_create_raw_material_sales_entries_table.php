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
        Schema::create('raw_material_sales_entries', function (Blueprint $table) {
            $table->id();
            $table->string('bill_date');
            $table->string('bill_no');
            $table->unsignedBigInteger("supplier_id")->index();
            $table->foreign("supplier_id")->references("id")->on("suppliers")->onDelete("cascade");
            $table->unsignedBigInteger("godam_id")->index();
            $table->foreign("godam_id")->references("id")->on("godam")->onDelete("cascade");
            $table->string('challan_no');
            $table->string('do_no');
            $table->string('gp_no');
            $table->string('through');
            $table->string('sale_for');
            $table->enum('status',['running', 'completed']);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('raw_material_sales_entries');
    }
};
