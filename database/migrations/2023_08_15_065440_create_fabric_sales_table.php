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
        Schema::create('fabric_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fabric_id");
            $table->foreign("fabric_id")->references("id")->on("fabrics")->onDelete("cascade");
            $table->unsignedBigInteger("sale_entry_id");
            $table->foreign("sale_entry_id")->references("id")->on("fabric_sale_entry")->onDelete("cascade");
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
        Schema::dropIfExists('fabric_sales');
    }
};
