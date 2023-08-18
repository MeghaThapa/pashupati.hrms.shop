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
        Schema::create('fabric_send_and_receive_temp_lam', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fsr_entry_id");
            $table->foreign("fsr_entry_id")->references("id")->on("fabric_send_and_receive_entry")->onDelete("cascade");
            $table->unsignedBigInteger("unlam_fabric_id");
            $table->foreign("unlam_fabric_id")->references("id")->on("fabric_send_and_receive_unlaminated_revised")->onDelete('cascade');
            $table->boolean('status')->nullable()->default(1);
            $table->string("fabric_name");
            $table->string("slug");
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
        Schema::dropIfExists('fabric_send_and_receive_temp_lam');
    }
};
