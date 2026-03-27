<?php

namespace App;
use App\Task;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
   
   public static function getTodayTask()
   {
   		$tasks=Task::where('takenDate',date('Y-m-d'))->orderBy('empId','asc')->get();
   		return $tasks;
   }
}
