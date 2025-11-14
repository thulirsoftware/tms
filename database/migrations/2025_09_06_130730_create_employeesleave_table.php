<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesleaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employeesleave', function (Blueprint $table) {
            $table->id();
            $table->string('empId', 20);
            $table->string('name', 255);
            $table->string('email', 50);
            $table->string('ph', 10);
            $table->string('el', 10);
            $table->string('al', 10);

            $table->integer('taken')->nullable();
            $table->integer('available')->nullable();
            $table->integer('compensate')->nullable();

            // Note: you had varchar(100) for created_at (unusual)
            $table->string('created_at', 100)->nullable();

            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employeesleave');
    }
}
