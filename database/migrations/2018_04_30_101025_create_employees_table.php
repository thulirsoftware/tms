<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('empId',20)->unique();
            $table->string('name');
            $table->string('email',50)->unique();
            $table->string('designation')->index();
            $table->string('mobile')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('motherTongue')->nullable();
            $table->string('qualification')->nullable();
            $table->string('expLevel')->nullable();
            $table->string('expYear')->nullable();
            $table->string('expMonth')->nullable();
            $table->string('bankAccountName')->nullable();
            $table->string('bankAccountNo')->nullable();
            $table->string('bankName')->nullable();
            $table->string('bankBranch')->nullable();
            $table->string('bankIfscCode')->nullable();
            $table->date('regDate')->nullable();
            $table->string('joinDate')->nullable();
            $table->string('resignDate')->nullable();
            $table->string('empStatus')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('employees');
    }
}
