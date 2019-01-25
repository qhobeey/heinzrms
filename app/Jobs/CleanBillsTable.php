<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use DB;

class CleanBillsTable implements ShouldQueue
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
    // DB::table('users')->where('active', false)
    //     ->chunkById(100, function ($users) {
    //         foreach ($users as $user) {
    //             DB::table('users')
    //                 ->where('id', $user->id)
    //                 ->update(['active' => true]);
    //         }
    //     });
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      DB::table('bills')->whereBetween('year', [2013, 2015])->chunk(500, function ($bills) {
        if ($bills->count() == 0) dd('No bills data found!');
        \App\Processing::create(['total' => \App\Bill::latest()->count(), 'count' => 0, 'percentage' => 0]);

        $groupBills = $bills->groupBy('account_no')->get();
        dd($groupBills);
          foreach ($bills as $bill) {



            $process = \App\Processing::first();
            if($process->count == 0) {
              $process->count == $process->count += 1;
            } else {
              $process->count == $process->count += 1;
            }
            $process->percentage = (int)(($process->count / $process->total) * 100);
            $process->save();
          }
      });
      return redirect()->route('processing');
    }
}
