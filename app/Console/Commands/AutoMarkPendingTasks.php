<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Task;
use App\InternTask;
use Carbon\Carbon;

class AutoMarkPendingTasks extends Command
{
    protected $signature = 'tasks:mark-pending';
    protected $description = 'Automatically mark running tasks as pending after 11:55 PM';

    public function handle()
    {
        $now = Carbon::now();
        $limitTime = Carbon::createFromTime(17, 00, 0);

        if ($now->greaterThanOrEqualTo($limitTime)) {
            // For employees
            $tasks = Task::where('status', 2) // Assuming 2 = 'In Progress'
                ->whereDate('takenDate', $now->toDateString())
                ->get();

            foreach ($tasks as $task) {
                $task->status = 3; // Assuming 3 = 'Pending'
                $task->save();
            }

            // For interns
            $internTasks = InternTask::where('status', 2)
                ->whereDate('takenDate', $now->toDateString())
                ->get();

            foreach ($internTasks as $task) {
                $task->status = 3;
                $task->save();
            }

            $this->info('All running tasks marked as pending successfully.');
        } else {
            $this->info('It is not yet 11:55 PM. Skipping task update.');
        }
    }
}
