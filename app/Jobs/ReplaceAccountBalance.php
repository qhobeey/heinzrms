<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReplaceAccountBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      \App\Processing::truncate();

      \App\Bill::orderBy('id')->where('year', 2018)->chunk(1000, function ($bills) {
        \App\Processing::create(['total' => \App\Bill::latest()->count(), 'count' => 0, 'percentage' => 0]);
        foreach ($bills as $key => $bill):

          $bill->current_amount = $bill->account_balance;
          $bill->account_balance = $bill->current_amount;

          $bill->save();

          $process = \App\Processing::first();
          if($process->count == 0) {
            $process->count == $process->count += 1;
          } else {
            $process->count == $process->count += 1;
          }
          $process->percentage = (int)(($process->count / $process->total) * 100);
          $process->save();

        endforeach;
      });
    }
}
