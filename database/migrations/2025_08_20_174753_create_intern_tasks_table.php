<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intern_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('assignedDate')->nullable(false);
            $table->date('takenDate')->nullable();

            $table->string('assignedBy', 255);
            $table->unsignedBigInteger('relatedTaskId')->nullable();
            $table->string('regularTaskId', 10)->nullable();

            $table->unsignedBigInteger('emplId');
            $table->unsignedBigInteger('projectId')->nullable();
            $table->unsignedBigInteger('activityId');

            $table->string('instruction', 255)->nullable();
            $table->string('priority', 10)->nullable();
            $table->string('comment', 255)->nullable();

            $table->time('startTime')->nullable();
            $table->time('endTime')->nullable();
            $table->string('hours', 255)->nullable();
            $table->string('minutes', 10)->nullable();

            $table->unsignedInteger('status')->default(1);
            $table->string('approval', 10)->nullable();

            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intern_tasks');
    }
}
