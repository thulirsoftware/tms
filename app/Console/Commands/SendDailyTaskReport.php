<?php


namespace App\Console\Commands;
use Mail;
use App\Report;
use App\Task;
use App\Employee;
use App\Project;
use App\CfgActivity;
use App\CfgDesignations;
use App\CfgTaskStatus;

use Illuminate\Console\Command;

class SendDailyTaskReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ln:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the daily task via email';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tasks=Report::getTodayTask();
        $designations=CfgDesignations::getDesignation();
        $projects=Project::getProjects();
        $activities=CfgActivity::getActivities();
        $taskStatus=CfgTaskStatus::getStatusList();
        $priorities=Task::getTaskPriorities();
        $employees=Employee::getEmployeeList();
        if(!empty($tasks))
        {
          

            $to='thulirsoft@gmail.com';

             Mail::send('report.emails.dailyTasks',['employees'=>$employees,'designations'=>$designations,'projects'=>$projects,'activities'=>$activities,'taskStatus'=>$taskStatus,'priorities'=>$priorities,'tasks'=>$tasks],function ($message) use($to) 
                {
                    $message->to($to)->subject('Daily Task Report - '.date('d-m-Y'));
                
                });
        }
    }
}
