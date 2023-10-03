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
        Schema::table('printing_and_cutting_bag_items', function (Blueprint $table) {
            $table->unsignedBigInteger("bag_fabric_receive_item_sent_stock_id")->nullable();

            // Define a custom name for the foreign key constraint
            $table->foreign("bag_fabric_receive_item_sent_stock_id", "custom_fk_name")
                ->references("id")->on('bag_fabric_receive_item_sent_stock')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('printing_and_cutting_bag_items', function (Blueprint $table) {
            $table->dropColumn('bag_fabric_receive_item_sent_stock_id');
        });
    }
};
