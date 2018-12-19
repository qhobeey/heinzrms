<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\PrepareBills;

use App\Http\Controllers\SetupController as Setup;


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


    public function propertyBills()
    {
      $bills = session()->get('bills');
      if(!$bills) $bills = \App\Bill::with('property')->where('bill_type', 'p')->where('year', date("Y"))->orderBy('account_no', 'asc')->paginate(50);
      return view('console.billing.property', compact('bills'));
    }


    public function postBills(Request $request)
    {
      dump($request->all());
      PrepareBills::dispatch($request->all());
      dd('p');
      // return redirect()->route('processing');
    }

    public static function initPropertyBill($property, $feefixing, $year)
    {
      // dd($property, $feefixing, $year);
      if($feefixing === date("Y")):
        $results = self::checkModelCategory($property, "property");
        if($results){
          $billResults = self::prepareBill($property->property_no, "p", $year, $results);
        };
      else:
        //not current fee fixing
        $results = self::checkFeefixingModelCategory($property, "property", $feefixing);
        if($results){
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
          $billResults = self::prepareBill($business->business_no, "b", $year, $results);
        };
      else:
        //not current fee fixing
        $results = self::checkFeefixingModelCategory($business, "business", $feefixing);
        if($results){
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
          if (!$model->category) {
            $response = $model->property_no;
            $z = \App\PropertyCategory::where('code', 'LIKE', "%{$model->property_category}%")->first();
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
            if(!$z) return dd("sorry fee fixing for year " .$feefixing." no found in database");
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
            if(!$z) return dd("sorry fee fixing for year " .$feefixing." no found in database");
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
      $amount = floatval($params['rateable_value']) * floatval($params['rate_pa']);
      $ans = $amount > $params['min_charge'] ? $amount : $params['min_charge'];
      $previousBill = \App\Bill::where('account_no', $account)->where('year', (string)(intval($year)-1))->first();
      $arrears = $previousBill ? floatval($previousBill->arrears) : floatval(0);

      $bill = array_merge($params, ['account_no' => $account,
          'account_balance' => $ans, 'arrears' => $arrears, 'current_amount' => $ans, 'bill_type' => $type, 'prepared_by' => 'Heinz', 'year' => $year,
          'bill_date' => Carbon::now()->toDateString()
      ]);
      unset($bill['min_charge']);

      $billRes = \App\Bill::where('account_no', $account)->where('year', $year)->first();
      if($billRes):
        $bill = array_merge($bill, ['arrears' => floatval($billRes->arrears)]);
        $billRes->update($bill);
      else:
        $t = \App\Bill::create($bill);
      endif;

      return true;
    }


}
