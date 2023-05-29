<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('purchase_date');
            $table->bigInteger('supplier_id')->unsigned()->index();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->string('purchase_code');
            $table->float('sub_total', 10, 2)->nullable();
            $table->float('discount', 10, 2)->nullable();
            $table->float('trasnport', 10, 2)->nullable();
            $table->float('total', 10, 2)->nullable();
            $table->float('total_paid', 10, 2)->nullable();
            $table->float('total_due', 10, 2)->nullable();
            $table->string('payment_type')->nullable();
            $table->string('purchase_image')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
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
        Schema::dropIfExists('purchases');
    }
}
