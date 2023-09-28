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
        Schema::create('dispatch_sauda_item_to_parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sauda_item_id');
            $table->date('dispatch_date');
            $table->enum('for', ['Local', 'Export']);
            $table->unsignedBigInteger('supplier_id');
            $table->string('party_acc');
            $table->unsignedBigInteger('delivery_order_for_item_id');
            $table->text('fabric_name')->nullable();
            $table->decimal('dispatch_qty', 10, 2);
            $table->enum('unit_name', ['Kgs', 'Pcs']);
            $table->decimal('rate', 10, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sauda_item_id')->references('id')->on('sauda_items');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('delivery_order_for_item_id', 'fk_dispatch_sauda_dofi')
                ->references('id')
                ->on('delivery_order_for_items')
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
        Schema::dropIfExists('dispatch_sauda_item_to_parties');
    }
};
