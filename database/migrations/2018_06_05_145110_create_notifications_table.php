<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID instead of int increments
            $table->string('type')->nullable();
            $table->string('notifiable_type')->nullable();
            $table->unsignedBigInteger('notifiable_id')->nullable();
            $table->text('data')->nullable();

            $table->unsignedInteger('fromUser')->nullable();
            $table->unsignedInteger('toUser')->nullable();

            $table->timestamp('read_at')->nullable(); // instead of boolean 'read'
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id']);

            $table->foreign('fromUser')
                ->references('id')->on('users')
                ->onDelete('restrict');

            $table->foreign('toUser')
                ->references('id')->on('users')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
