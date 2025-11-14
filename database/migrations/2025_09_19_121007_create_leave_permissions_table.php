<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('empId'); // link to employees
            $table->string('name');
            $table->string('email');
            $table->date('requestDate');
            $table->date('permissionDate');
            $table->time('fromTime');
            $table->time('toTime');
            $table->decimal('totalHours', 5, 2); // e.g. 2.50 hours
            $table->text('reason')->nullable();
            $table->enum('approval', ['pending', 'yes', 'no'])->default('pending');
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
        Schema::dropIfExists('leave_permissions');
    }
}
