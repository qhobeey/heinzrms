<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobs\CleanBillsTable;
use App\Jobs\FloatCleanBills;
use App\Jobs\SetBillLocation;

class ConsoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect()->route('console.dashboard');
    }

    public function dashboard()
    {
        // return view('console.prints.index');
        return view('console.console');
    }
    public function back()
    {
        return redirect()->back();
    }

    public function construction()
    {
      \App\Bill::whereNull('zonal_id')->latest()->chunk(1000, function ($bills) {
        \App\Processing::create(['total' => \App\Bill::whereNull('zonal_id')->latest()->count(), 'count' => 0, 'percentage' => 0]);
        foreach ($bills as $key => $bill):
          $property = \App\Property::where('property_no', $bill->account_no)->first();
          if(!$property) return;
          // dd($property, 'good');
          $bill->zonal_id = $property->zonal_id;
          $bill->electoral_id = $property->electoral_id;
          $bill->tas_id = $property->tas_id;
          $bill->community_id = $property->community_id;

          $bill->save();

          // $process = \App\Processing::first();
          // if($process->count == 0) {
          //   $process->count == $process->count += 1;
          // } else {
          //   $process->count == $process->count += 1;
          // }
          // $process->percentage = (int)(($process->count / $process->total) * 100);
          // $process->save();

        endforeach;
      });


      SetBillLocation::dispatch();
      // FloatCleanBills::dispatch();
      // dd('p');
      return redirect()->route('processing');
        // return view('console.construction');
    }
}
