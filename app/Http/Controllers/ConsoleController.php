<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobs\CleanBillsTable;
use App\Jobs\FloatCleanBills;
use App\Jobs\SetBillLocation;
use App\Jobs\ReplaceAccountBalance;

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

    public function construction2()
    {



      // SetBillLocation::dispatch();
      FloatCleanBills::dispatch();
      // dd('p');
      return redirect()->route('processing');
        // return view('console.construction');
    }
    public function construction()
    {



      ReplaceAccountBalance::dispatch();
      // FloatCleanBills::dispatch();
      // dd('p');
      return redirect()->route('processing');
        // return view('console.construction');
    }
    public function construction3()
    {



      SetBillLocation::dispatch();
      // FloatCleanBills::dispatch();
      // dd('p');
      return redirect()->route('processing');
        // return view('console.construction');
    }
}
