<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;

class SetBillLocation implements ShouldQueue
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

      \App\Bill::whereNull('zonal_id')->latest()->chunk(1000, function ($bills) {
        \App\Processing::create(['total' => \App\Bill::whereNull('zonal_id')->latest()->count(), 'count' => 0, 'percentage' => 0]);
        foreach ($bills as $key => $bill):
          $property = \App\Property::where('property_no', $bill->account_no)->first();
          if(!$property) return;
          $bill->zonal_id = $property->zonal_id;
          $bill->electoral_id = $property->electoral_id;
          $bill->tas_id = $property->tas_id;
          $bill->community_id = $property->community_id;

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
