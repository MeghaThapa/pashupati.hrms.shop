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
        Schema::create('fabric_send_and_receive_laminated_sent', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fsr_entry_id");
            $table->foreign("fsr_entry_id")->references("id")->on("fabric_send_and_receive_entry")->onDelete('cascade');
            $table->string("fabric_name");
            $table->string("slug");
            $table->string("net_wt");
            $table->string("average_wt");
            $table->string("gross_wt");
            $table->string("gram_wt");
            $table->string("meter");
            $table->unsignedBigInteger("fabricgroup_id");
            $table->foreign("fabricgroup_id")->references("id")->on("fabric_groups")->onDelete('cascade');
            $table->string("standard_wt");
            $table->string("loom_no");
            $table->string("roll_no");
            $table->unsignedBigInteger('fabid')->nullable();
            $table->foreign("fabid")->references("id")->on("fabrics")->onDelete("cascade");
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
        Schema::dropIfExists('fabric_send_and_receive_laminated_sent');
    }
};
