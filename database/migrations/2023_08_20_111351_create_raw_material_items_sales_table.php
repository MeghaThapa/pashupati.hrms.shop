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
        Schema::create('raw_material_items_sales', function (Blueprint $table) {
            $table->id();
            $table->string('lorry_no');
            $table->unsignedBigInteger("raw_material_sales_entry_id")->index();
            $table->foreign("raw_material_sales_entry_id")->references("id")->on("raw_material_sales_entries")->onDelete("cascade");
            $table->unsignedBigInteger("dana_group_id")->index();
            $table->foreign("dana_group_id")->references("id")->on("godam")->onDelete("cascade");
            $table->unsignedBigInteger("dana_name_id")->index();
            $table->foreign("dana_name_id")->references("id")->on("dana_groups")->onDelete("cascade");
            $table->string('qty_in_kg');
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
        Schema::dropIfExists('raw_material_items_sales');
    }
};
