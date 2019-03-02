<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\PrepareBills;
use App\BillSetting as Setting;

use App\Http\Controllers\SetupController as Setup;

use App\WebClientPrint\WebClientPrint;
use App\WebClientPrint\Utils;
use App\WebClientPrint\DefaultPrinter;
use App\WebClientPrint\InstalledPrinter;
use App\WebClientPrint\PrintFile;
use App\WebClientPrint\ClientPrintJob;

use Session;
use Cloudder;


class BillingController extends Controller
{

    function __construct()
    {
      $this->middleware('auth');
    }

    public function processing()
    {
      return view('console.billing.processing');
    }

    public function propertyBillPrepare()
    {
      return view('console.billing.property.prepare-bills');
    }
    public function propertyBillPrepareBulk()
    {
      $bills = [];
      $setting = Setting::latest()->first();
      $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());
      return view('console.billing.property.bulk-print', compact('bills', 'setting', 'wcpScript'));
    }

    public function filterBillsQuery(Request $request, $query=null)
    {
      // dd($request->all());
      $setting = Setting::latest()->first();
      $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());
      if($request->account_no):
        $bills = \App\Bill::where('account_no', $request->account_no)->where('bill_type', 'LIKE', "%{$request->bill_type}%")->where('year', $request->year)->get();

        return view('console.billing.property.bulk-print', compact('bills', 'setting', 'wcpScript'));
      endif;
      if($request->electoral_id):
        $bills = \App\Bill::where('electoral_id', $request->electoral_id)->where('bill_type', $request->bill_type)->where('year', $request->year)->orderBy('account_no', 'asc')->get();
        // dd($bills);
        return view('console.billing.property.bulk-print', compact('bills', 'setting', 'wcpScript'));
      endif;
      return redirect()->back();
    }


    public function propertyBills()
    {
      $bills = session()->get('bills');
      // if(!$bills) $bills = \App\Bill::with(['property', 'business'])->where('bill_type', 'p')->where('year', date("Y"))->orderBy('account_no', 'asc')->paginate(50);
      if(!$bills) $bills = \App\Bill::with(['property', 'business'])->orderBy('account_no', 'asc')->paginate(50);
      // dd($bills);
      return view('console.billing.property', compact('bills'));
    }

    public function filterBillsByColumn(Request $request)
    {

      $query = '';
      $queryArray = [];
      $businesses = [];
      $array = null;
      $reqs = $request->validate(['column' => 'required', 'query' => '']);

      switch ($request->column) {
        case 'account_no':
          $query = $reqs['query'];
          break;
        case 'owner_name':
          $query = $reqs['query'];
          break;
        case 'phone_number':
          $queryArray = \App\PropertyOwner::where('phone', 'LIKE', "%{$reqs['query']}%")->pluck('code');
          break;
        case 'account_type':
          $query = $reqs['query'];
          break;
        case 'account_category':
          $query = $reqs['query'];
          break;
        case 'year':
          $query = $reqs['query'];
          break;

        default:
          $query = '';
          break;
      }



      $bills = \App\Bill::with(['property', 'business'])->where($request->column, 'LIKE', "%{$query}%")->paginate(50);

      return view('console.billing.property', compact('bills'));
    }


    public function postBills(Request $request)
    {
      // dump($request->all());
      PrepareBills::dispatch($request->all());
      // dd('p');
      return redirect()->route('processing');
    }

    public function postBillsPerUnit(Request $request)
    {
      // dd($request->all());
      if($request->account_no == '' || $request->account_no == null) return redirect()->back()->with('Error', 'Account no can\'t be empty');
      $data = $request->validate(['account_no' => 'required']);
      if(strtolower($request->account) == strtolower('property')):
        $property = \App\Property::where('property_no', $request->account_no)->first();
        if(!$property) return redirect()->back()->with('Error', 'Account not found!');
        $response = self::initPropertyBill($property, $request->feefixing, $request->year);
        if($response):
          return redirect()->back()->with('success', 'Bill successfully generated');
        else:
          return redirect()->back()->with('Error', 'Error in generating bill');
        endif;
      endif;
    }

    public static function initPropertyBill($property, $feefixing, $year)
    {
      // dd($property, $feefixing, $year);
      if($feefixing === date("Y")):
        $results = self::checkModelCategory($property, "property");
        if($results){
          $results = array_merge($results,[
            'zonal_id'=>$property->zonal_id ?: '', 'tas_id'=>$property->tas_id ?: '',
            'electoral_id'=>$property->electoral_id ?: '', 'community_id'=>$property->community_id ?: ''
          ]);
          $billResults = self::prepareBill($property->property_no, "p", $year, $results);
        };
      else:
        //not current fee fixing
        $results = self::checkFeefixingModelCategory($property, "property", $feefixing);
        if($results){
          $results = array_merge($results,[
            'zonal_id'=>$property->zonal_id ?: '', 'tas_id'=>$property->tas_id ?: '',
            'electoral_id'=>$property->electoral_id ?: '', 'community_id'=>$property->community_id ?: ''
          ]);
          $billResults = self::prepareBill($property->property_no, "p", $year, $results);
        };
      endif;

      return true;
    }

    public static function initBusinessBill($business, $feefixing, $year)
    {
      if($feefixing === date("Y")):
        $results = self::checkModelCategory($business, "business");
        if($results){
          $results = array_merge($results,[
            'zonal_id'=>$business->zonal_id ?: '', 'tas_id'=>$business->tas_id ?: '',
            'electoral_id'=>$business->electoral_id ?: '', 'community_id'=>$business->community_id ?: ''
          ]);
          $billResults = self::prepareBill($business->business_no, "b", $year, $results);
        };
      else:
        //not current fee fixing
        $results = self::checkFeefixingModelCategory($business, "business", $feefixing);
        if($results){
          $results = array_merge($results,[
            'zonal_id'=>$business->zonal_id ?: '', 'tas_id'=>$business->tas_id ?: '',
            'electoral_id'=>$business->electoral_id ?: '', 'community_id'=>$business->community_id ?: ''
          ]);
          $billResults = self::prepareBill($business->business_no, "b", $year, $results);
        };
      endif;

      return true;
    }

    public static function checkModelCategory($model, $type)
    {
        $params = [];
        $response;
        if(strtolower($type) == "property"):
          // dd($model);
          // dd($model->category);
          if (!$model->category) {
            $response = $model->property_no;
            $z = \App\PropertyCategory::where('code', 'LIKE', "%{$model->property_category}%")->first();
            if(!$z) return false;
            if(!floatval($z->min_charge)) return false;
            $params = array_merge($params, ['min_charge' => floatval($z->min_charge),
                'rate_pa' => floatval($z->rate_pa),
                'rateable_value' => floatval($model->rateable_value)
            ]);
          }else{
            $params = array_merge($params, ['min_charge' => floatval($model->category->min_charge),
                'rate_pa' => floatval($model->category->rate_pa),
                'rateable_value' => floatval($model->rateable_value)
            ]);
          }
        endif;

        if(strtolower($type) == "business"):
          if (!$model->category) {
            $response = $model->business_no;
            $z = \App\BusinessCategory::where('code', 'LIKE', "%{$model->business_category}%")->first();
            if(!$z) return false;
            $params = array_merge($params, ['min_charge' => floatval($z->min_charge),
                'rate_pa' => floatval($z->rate_pa),
                'rateable_value' => floatval($model->rateable_value)
            ]);
            if(!floatval($z->min_charge)) dd('ol');
            // dd($params);
          }else{
            $params = array_merge($params, ['min_charge' => floatval($model->category->min_charge),
                'rate_pa' => floatval($model->category->rate_pa),
                'rateable_value' => floatval($model->rateable_value)
            ]);
          }
        endif;

        return $params;
    }

    public static function checkFeefixingModelCategory($model, $type, $feefixing)
    {
        $params = [];
        $response;

        if(strtolower($type) == "property"):
          $category = \App\PropertyFeefixingCategory::where('code', $model->property_category)->where('year', $feefixing)->first();
          if (!$category) {
            $response = $model->property_no;
            $z = \App\PropertyFeefixingCategory::where('code', 'LIKE', "%{$model->property_category}%")->where('year', $feefixing)->first();
            if(!$z) return false;
            $params = array_merge($params, ['min_charge' => floatval($z->min_charge),
                'rate_pa' => floatval($z->rate_pa),
                'rateable_value' => floatval($model->rateable_value)
            ]);
            if(!$z->min_charge) dd('ol');
            // dd($params);
          }else{
            $params = array_merge($params, ['min_charge' => floatval($category->min_charge),
                'rate_pa' => floatval($category->rate_pa),
                'rateable_value' => floatval($model->rateable_value)
            ]);
          }
        endif;

        if(strtolower($type) == "business"):
          $category = \App\BusinessFeefixingCategory::where('code', $model->business_category)->where('year', $feefixing)->first();
          if (!$category) {
            $response = $model->property_no;
            $z = \App\PropertyFeefixingCategory::where('code', 'LIKE', "%{$model->business_category}%")->where('year', $feefixing)->first();
            if(!$z) return false;
            $params = array_merge($params, ['min_charge' => floatval($z->min_charge),
                'rate_pa' => floatval($z->rate_pa),
                'rateable_value' => floatval($model->rateable_value)
            ]);
            if(!$z->min_charge) dd('ol');
            // dd($params);
          }else{
            $params = array_merge($params, ['min_charge' => floatval($category->min_charge),
                'rate_pa' => floatval($category->rate_pa),
                'rateable_value' => floatval($model->rateable_value)
            ]);
          }
        endif;

        return $params;
    }

    public static function prepareBill($account, $type, $year, $params)
    {

      $imposed = floatval($params['rate_pa']);
      $amount = floatval($params['rateable_value']) * $imposed;
      $ans = $amount > $params['min_charge'] ? $amount : $params['min_charge'];
      $previousBill = \App\Bill::where('account_no', $account)->where('year', (string)(intval($year)-1))->first();
      $arrears = $previousBill ? floatval($previousBill->account_balance) : floatval(0);
      $totalPaid = \App\Payment::where('account_no', $account)->where('payment_year', $year)->sum('amount_paid');
      $lastYearTotalPaid = \App\Payment::where('account_no', $account)->where('payment_year', (string)(intval($year) - 1))->sum('amount_paid');
      $lastYearBill = $previousBill ? floatval($previousBill->rate_pa) : floatval(0);
      unset($params['rate_pa']);

      $bill = array_merge($params, ['account_no' => $account,
          'account_balance' => ($ans + $arrears) - $totalPaid, 'arrears' => $arrears, 'current_amount' => $ans, 'bill_type' => $type, 'prepared_by' => 'admin', 'year' => $year,
          'bill_date' => Carbon::now()->toDateString(), 'rate_imposed' => $imposed, 'rate_pa' => number_format((float)$ans, 2, '.', ''),
          'total_paid' => number_format((float)$totalPaid, 2, '.', ''), 'p_year_bill' => $lastYearBill, 'p_year_total_paid' => $lastYearTotalPaid, 'printed' => 0
      ]);
      unset($bill['min_charge']);

      $billRes = \App\Bill::where('account_no', $account)->where('year', $year)->first();
      if($billRes):
        // $bill = array_merge($bill, ['arrears' => $arrears, 'account_balance' => $ans + $arrears]);
        $billRes->update($bill);
      else:
        $t = \App\Bill::create($bill);
      endif;

      return true;
    }


}
