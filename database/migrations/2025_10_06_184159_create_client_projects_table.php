<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_projects', function (Blueprint $table) {
            $table->id(); 
            $table->string('project_id', 20)->unique();
            $table->string('project_name');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->text(column: 'project_desc')->nullable();
            $table->date('start_date');
            $table->enum('status', ['Open','Closed'])->default('Open');
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_projects');
    }
}
