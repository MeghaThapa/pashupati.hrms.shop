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
        Schema::create('bag_selling_entries', function (Blueprint $table) {
            $table->id();
            $table->string('challan_no');
            $table->string('date');
            $table->string('nepali_date');
            $table->unsignedBigInteger("supplier_id");
            $table-> foreign("supplier_id")->references("id")->on('suppliers')->onDelete('cascade');
            $table->string('gp_no');
            $table->string('lorry_no');
            $table->string('do_no');
            $table->string('rem');
            $table->enum('status',['running','completed']);
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
        Schema::dropIfExists('bag_selling_entries');
    }
};
