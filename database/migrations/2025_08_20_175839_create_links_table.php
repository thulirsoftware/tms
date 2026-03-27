<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->bigIncrements('id'); // UNSIGNED AUTO_INCREMENT (Primary Key)
            $table->date('date'); // Not nullable
            $table->string('senderId', 255); // Not nullable
            $table->unsignedBigInteger('receiverId'); // Not nullable
            $table->string('description', 255)->nullable();
            $table->string('link', 255)->nullable();

            $table->enum('deleted_at', ['Y', 'N'])->default('N'); // Your table uses enum instead of softDeletes

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
    }
}
