<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobs\CleanBillsTable;
use App\Jobs\FloatCleanBills;

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
      // DB::table('bills')->whereBetween('year', [2007, 2015])->latest()->chunk(500, function ($bills) {
      //   if ($bills->count() == 0) dd('No bills data found!');
      //
      //   foreach ($bills as $key => $value) {
      //     dump($bills->count());
      //     unset($bills[0]);
      //   };
      //   dd($bills->count());

        // $filterAccount = $bills[0]->account_no;
        // $billSet = $bills->where('account_no', $filterAccount);
        //
        // $billArrayInsert = [
        //   'account_no' => $filterAccount, 'rate_pa' => $billSet->where('year', $billSet->max('year'))->first()->rate_pa,
        //   'rateable_value' => $billSet->where('year', $billSet->max('year'))->first()->rateable_value,
        //   'current_amount' => $billSet->where('year', $billSet->max('year'))->first()->current_amount,
        //   'arrears' => $billSet->where('year', $billSet->max('year'))->first()->arrears,
        //   'rate_imposed' => $billSet->where('year', $billSet->max('year'))->first()->rate_imposed,
        //   'total_paid' => $billSet->where('year', $billSet->max('year'))->first()->total_paid,
        //   'bill_type' => $billSet->where('year', $billSet->max('year'))->first()->bill_type,
        //   'year' => $billSet->where('year', $billSet->max('year'))->first()->year,
        //   'account_balance' => $billSet->where('year', $billSet->max('year'))->first()->account_balance,
        //   'bill_date' => $billSet->where('year', $billSet->max('year'))->first()->bill_date,
        //   'adjust_arrears' => $billSet->where('year', $billSet->max('year'))->first()->adjust_arrears,
        //   'prepared_by' => 'ADMINISTRATOR',
        //   'accumulated_current_amount' => $billSet->max('current_amount'),
        //   'accumulated_arrears' => $billSet->max('arrears'),
        //   'accumulated_account_balance' => $billSet->max('account_balance'),
        //   'accumulated_adjust_arrears' => $billSet->max('adjust_arrears'),
        //   'accumulated_total_paid' => $billSet->max('total_paid'),
        // ];
        // dd($billArrayInsert);
        // $isCreated = \App\Bill::create($billArrayInsert);
        // if($isCreated):
        //   \App\Bill::where('account_no', $filterAccount)->first()->delete();
        // endif;
      // });


      FloatCleanBills::dispatch();
      // FloatCleanBills::dispatch();
      // dd('p');
      return redirect()->route('processing');
        // return view('console.construction');
    }
}
