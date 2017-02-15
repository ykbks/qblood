<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('gender',['male','female']);
            $table->enum('religion', ['islam','hindu','buddhist','christian']);
            $table->date('dob')->nullable();
            $table->enum('reg_type',['qa','qg','qpm']);
            $table->enum('blood_group',['A+', 'A-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->integer('reg_no')->nullable();
            $table->integer('reg_batch')->nullable();
            $table->string('reg_batch_suffix')->nullable();
            $table->string('reg_complete')->nullable();
            $table->string('phone_primary', 20)->unique();
            $table->string('phone_secondary')->nullable(); 
            $table->string('phone_request')->nullable();
            $table->boolean('can_donate')->default(1);
            $table->boolean('available')->default(1);
            $table->date('unavailable_till')->nullable();
            $table->integer('area_id')->nullable();
            $table->string('address',500)->nullable();
            $table->string('added_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('donors');
    }
}
