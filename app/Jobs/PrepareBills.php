<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Http\Controllers\BillingController as Billing;

class PrepareBills implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \App\Processing::truncate();

        if($this->request['account'] == "property"):
          $properties = \App\Property::latest()->get();
          if ($properties->count() == 0) dd('No Property data found!');
          \App\Processing::create(['total' => $properties->count(), 'count' => 0, 'percentage' => 0]);
          foreach($properties as $key => $property):
            Billing::initPropertyBill($property, $this->request['feefixing'], $this->request['year']);
            $process = \App\Processing::first();
            if($process->count == 0) {
              $process->count == $process->count += 1;
            } else {
              $process->count == $process->count += 1;
            }
            $process->percentage = (int)(($process->count / $process->total) * 100);
            $process->save();
          endforeach;
        else:
          $businesses = \App\Business::latest()->get();
          if ($businesses->count() == 0) dd('No business data found!');
          \App\Processing::create(['total' => $businesses->count(), 'count' => 0, 'percentage' => 0]);
          foreach($businesses as $business):
              Billing::initBusinessBill($business, $this->request['feefixing'], $this->request['year']);
              $process = \App\Processing::first();
              if($process->count == 0) {
                $process->count == $process->count += 1;
              } else {
                $process->count == $process->count += 1;
              }
              $process->percentage = (int)(($process->count / $process->total) * 100);
              $process->save();
          endforeach;
          // $bills = \App\Bill::where('bill_type', 'b')->where('year', $this->request['year'])->orderBy('account_no', 'asc')->get();
          // $resMessage = $bills->count()." bills were generated";
          // dd($resMessage);
          // Log::info("Request ended.......");
          // echo '<script language="javascript">';
          // echo 'alert("Total of: '.$resMessage.'")';
          // echo '</script>';
          return redirect()->route('processing');
        endif;
    }
}
