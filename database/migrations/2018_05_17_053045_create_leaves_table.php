<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->date('requestDate');  

            $table->integer('empId')->unsigned();
            $table->index('empId');
            $table->foreign('empId')->references('id')->on('employees')->onDelete('RESTRICT');
            
            $table->integer('leaveTypeId')->unsigned();
            $table->index('leaveTypeId');
            $table->foreign('leaveTypeId')->references('id')->on('cfg_leave_types')->onDelete('RESTRICT');

            $table->date('leaveFromDate')->nullable();  
            $table->date('leaveToDate')->nullable();  
            $table->string('totalLeaveDays')->nullable();
            $table->string('availLeaveDays')->nullable();
            $table->string('reason')->nullable();
            $table->string('comment')->nullable();
            $table->string('approval')->nullable();
            $table->string('approvalBy')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaves');
    }
}
