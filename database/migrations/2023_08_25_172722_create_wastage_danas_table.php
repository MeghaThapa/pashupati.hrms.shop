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
        Schema::create('wastage_danas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("reprocess_wastage_id");
            $table->foreign("reprocess_wastage_id")->references("id")->on("reprocess_wastes")->onDelete("cascade");
            $table->unsignedBigInteger("planttype_id");
            $table->foreign("planttype_id")->references("id")->on("processing_steps")->onDelete("cascade");
            $table->unsignedBigInteger("plantname_id");
            $table->foreign("plantname_id")->references("id")->on("processing_subcats")->onDelete("cascade");
            $table->unsignedBigInteger("dana_id");
            $table->foreign("dana_id")->references("id")->on("dana_names")->onDelete("cascade");
            $table->unsignedBigInteger("dana_group_id");
            $table->foreign("dana_group_id")->references("id")->on("dana_groups")->onDelete("cascade");
            $table->string("quantity");
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
        Schema::dropIfExists('wastage_danas');
    }
};
