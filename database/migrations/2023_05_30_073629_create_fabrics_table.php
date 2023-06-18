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
        Schema::create('fabrics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->bigInteger('fabricgroup_id')->unsigned()->index();
            $table->foreign('fabricgroup_id')->references('id')->on('fabric_groups')->onDelete('cascade');
            $table->unsignedBigInteger('godam_id');
            $table->foreign('godam_id')->references('id')->on("godam")->onDelete('cascade');
            $table->string('gram');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('meter');
            $table->string('roll_no');
            $table->string('loom_no');
            $table->boolean('status')->nullable()->default(1);
            $table->enum("is_laminated",["true","false"])->default("false");
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
        Schema::dropIfExists('fabrics');
    }
};
