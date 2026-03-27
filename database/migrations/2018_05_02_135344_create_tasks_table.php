0<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->date('assignedDate');  
            $table->date('takenDate')->nullable();;            
            $table->string('assignedBy');          
            $table->integer('relatedTaskId')->unsigned();
            $table->integer('empId')->unsigned();
            $table->index('empId');
            $table->foreign('empId')->references('id')->on('employees')->onDelete('RESTRICT');
            $table->integer('projectId')->unsigned();
            $table->index('projectId');
            $table->foreign('projectId')->references('id')->on('projects')->onDelete('RESTRICT');
            $table->integer('activityId')->unsigned();
            $table->index('activityId');
            $table->foreign('activityId')->references('id')->on('cfg_activities')->onDelete('RESTRICT');
            $table->string('instruction')->nullable();
            $table->string('priority')->nullable();
            $table->string('comment')->nullable();
            $table->time('startTime');
            $table->time('endTime');
            $table->string('hours')->nullable();
            $table->string('minutes')->nullable();
            $table->integer('status')->unsigned();
            $table->index('status');
            $table->foreign('status')->references('id')->on('cfg_task_statuses')->onDelete('RESTRICT');
            $table->string('approval')->nullable();
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('tasks');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
