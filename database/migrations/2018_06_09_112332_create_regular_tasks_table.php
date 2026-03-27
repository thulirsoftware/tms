<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegularTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regular_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->date('assignedDate');              
            $table->date('takenDate'); 
            $table->string('assignedBy'); 
            $table->string('taskType');         
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
        Schema::dropIfExists('regular_tasks');
    }
}
