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
        Schema::create('fabric_send_and_receive_unlaminated_revised', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fsr_entry_id");
            $table->unsignedBigInteger('fabric_id');
            $table->foreign("fsr_entry_id")->references("id")->on("fabric_send_and_receive_entry")->onDelete("cascade");
            $table->foreign('fabric_id')->references("id")->on('fabrics')->onDelete('cascade')->onUpdate('cascade');
            // $table->string('roll_no');
            // $table->string('gross_wt');
            // $table->string('net_wt');
            // $table->string('meter');
            // $table->string('average')->nullable();
            // $table->string('gram');
            $table->enum("status",['pending',"sent"])->default("pending");
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
        Schema::dropIfExists('fabric_send_and_receive_unlaminated_revised');
    }
};
