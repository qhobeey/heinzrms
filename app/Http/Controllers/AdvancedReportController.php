<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Reports\ReportListing as Report;

use App\Models\Location\Zonal;
use App\Models\Location\Electoral;

use App\Reports\ElectoralPropertyReport;
use App\Reports\ElectoralBusinessReport;
use App\Reports\PropertyReport;
use App\Reports\BusinessReport;

use App\Property;
use App\Bill;

use DB;
use URL;

use App\WebClientPrint\WebClientPrint;
use App\WebClientPrint\Utils;
use App\WebClientPrint\DefaultPrinter;
use App\WebClientPrint\InstalledPrinter;
use App\WebClientPrint\PrintFile;
use App\WebClientPrint\ClientPrintJob;

use Session;
use Cloudder;

// use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class AdvancedReportController extends Controller
{


    public function __construct(Request $request, Zonal $zonal, PropertyReport $property, ElectoralPropertyReport $electoralProperty, ElectoralBusinessReport $electoralBusiness, Bill $bill, BusinessReport $business)
    {
      $this->zonal = $zonal;
      $this->property = $property;
      $this->electoralProperty = $electoralProperty;
      $this->electoralBusiness = $electoralBusiness;
      $this->bill = $bill;
      $this->request = $request;
      $this->business = $business;

      $this->middleware('auth');
    }


    public function test()
    {
      // dd('ok');
      // return $request->all();
      // $electoral = $this->electoral->with(['properties'])->get()->groupBy('description');
      // $electoral = $this->electoral->with(['properties','properties.bills'])->paginate(2)->groupBy('description');
      // $test = $this->property->get();
      $test = DB::table('properties')->get();
      // $zonal = $this->zonal->with('electorals')->first();
      // dd($electoral);

      // return $electoral;
      return ['result'=>$test];
    }

    public function propertyListingSearch()
    {
      return view('advanced.report.property.search-board');
    }

    public function propertyListing(Request $request)
    {

      // dd($request->url());

       if($request->loc != "a") return $this->propertySearchListingDetails($request->all());

       $location = $request['location'];
       $year = $request['year'];

       $electorals = $this->electoralProperty->with(['bills'=>function($query) use ($year) {
          $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'));
       }])->paginate(50)->appends(request()->query());
      // return ['result'=>$electorals];
      $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());

      return view('advanced.report.property.property-listing', compact('electorals', 'location', 'year', 'wcpScript'));
    }

    public function propertySearchListingDetails($data)
    {
      // dd($data);

      $location = '';
      $year = '';
      $loc = '';
      if (array_key_exists('location', $data)) {
        // dd('o');
          $location = $data['location'] ? : '';
          $url = url('/').'/console/advanced/report/property/'.$data['location'].'/'.$data['loc'].'/'.$data['year'].'/';
      }else{
          $parts = parse_url(URL::previous());
          parse_str($parts['query'], $query);
          $url = url('/').'/console/advanced/report/property/'.$query['location'].'/'.$query['loc'].'/'.$query['year'].'/';
          // dd($url);
      }
      if (array_key_exists('year', $data)) {
          $year = $data['year'] ? : '';
      }
      if (array_key_exists('loc', $data)) {
          $loc = $data['loc'] ? : '';
      }

      // dd($loc);

      // $electoral = $this->electoralProperty->where('code', $loc)->get();
      $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());
      $electoral = $this->electoralProperty->where('code', $loc)->whereHas('bills', function($q) use ($year) {
        $q->where('year', $year);
      })->with(['bills' => function($query) use ($year) {
        $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'));
      }])->first();
      $bills = $electoral ? $this->paginate($electoral->bills, $perPage = 30, $page = null, $baseUrl = $url, $options = []) : [];
      $info = $electoral ? $electoral->description : '';
      $totalBill = $electoral ? $electoral->bills->count(): '';
      // dd($electoral->bills->count());

      // return ['result'=>$bills];
      return view('advanced.report.property.property-listing-details', compact('bills', 'year', 'location', 'wcpScript', 'info', 'totalBill', 'electoral'));
    }





    public function propertyListingDetails(Request $request, $location, $code, $year)
    {

      $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());
      $electoral = $this->electoralProperty->where('code', $code)->whereHas('bills', function($q) use ($year) {
        $q->where('year', $year);
      })->with(['bills' => function($query) use ($year) {
        $query->with('properties')->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'));
      }])->first();

      $bills = $electoral ? $this->paginate($electoral->bills, $perPage = 30, $page = null, $baseUrl = $request->url().'/', $options = []) : [];
      $info = $electoral ? $electoral->description : '';
      $totalBill = $electoral ? $electoral->bills->count(): '';
      // return ['result'=> $electoral->bills->sum('arrears')];
      // dd($year, $location, $code, $info);
      return view('advanced.report.property.property-listing-details', compact('bills', 'year', 'location', 'info', 'wcpScript', 'totalBill', 'electoral'));
    }

    public function apiPropertyListing()
    {
      $data = \App\Bill::with(['property'])->limit('4')->get()->groupBy('electoral_name');
      // $data = \App\Bill::with(['property'])->limit('4')->get()->sortBy('property.electoral.description');
      return response()->json(['data' => $data]);
    }

    // public function paginate($items, $perPage = 15, $page = null, $baseUrl = null, $options = [])
    // {

    //   $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
    //   $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
    //   $lap = new \Illuminate\Pagination\LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

    //   if ($baseUrl) {
    //         $lap->setPath($baseUrl);
    //     }

    //     return $lap;
    // }










    /** Business Listings */

    public function businessListingSearch()
    {
      return view('advanced.report.business.search-board');
    }



    public function businessListing(Request $request)
    {


       if($request->loc != "a") return $this->businessSearchListingDetails($request->all());

       $location = $request['location'];
       $year = $request['year'];

       $electorals = $this->electoralBusiness->whereHas('bills', function($q) use ($year) {
         $q->where('year', $year);
       })->with(['bills'=>function($query) use ($year) {
          $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('b'));
       }])->paginate(10)->appends(request()->query());
      // return ['result'=>$electorals];
      $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());

      return view('advanced.report.business.business-listing', compact('electorals', 'location', 'year', 'wcpScript'));
    }

    public function businessSearchListingDetails($data)
    {
      // dd($data);

      $location = '';
      $year = '';
      $loc = '';
      if (array_key_exists('location', $data)) {
        // dd('o');
          $location = $data['location'] ? : '';
          $url = url('/').'/console/advanced/report/business/'.$data['location'].'/'.$data['loc'].'/'.$data['year'].'/';
      }else{
          $parts = parse_url(URL::previous());
          parse_str($parts['query'], $query);
          $url = url('/').'/console/advanced/report/business/'.$query['location'].'/'.$query['loc'].'/'.$query['year'].'/';
          // dd($url);
      }
      if (array_key_exists('year', $data)) {
          $year = $data['year'] ? : '';
      }
      if (array_key_exists('loc', $data)) {
          $loc = $data['loc'] ? : '';
      }

      // dd($loc);

      // $electoral = $this->electoralProperty->where('code', $loc)->get();
      $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());
      $electoral = $this->electoralBusiness->where('code', $loc)->whereHas('bills', function($q) use ($year) {
        $q->where('year', $year);
      })->with(['bills' => function($query) use ($year) {
        $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('b'));
      }])->first();
      $bills = $electoral ? $this->paginate($electoral->bills, $perPage = 30, $page = null, $baseUrl = $url, $options = []) : [];
      $info = $electoral ? $electoral->description : '';
      $totalBill = $electoral ? $electoral->bills->count() : '';
      // dd($electoral->bills->count());

      // return ['result'=>$bills];
      return view('advanced.report.business.business-listing-details', compact('bills', 'year', 'location', 'wcpScript', 'info', 'totalBill', 'electoral'));
    }





    public function businessListingDetails(Request $request, $location, $code, $year)
    {

      $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());
      $electoral = $this->electoralBusiness->where('code', $code)->whereHas('bills', function($q) use ($year) {
        $q->where('year', $year);
      })->with(['bills' => function($query) use ($year) {
        $query->with('businesses')->where('year', $year)->where(strtoupper('bill_type'), strtoupper('b'));
      }])->first();

      $bills = $electoral ? $this->paginate($electoral->bills, $perPage = 20, $page = null, $baseUrl = $request->url().'/', $options = []) : [];
      $info = $electoral ? $electoral->description : '';
      $totalBill = $electoral ? $electoral->bills->count() : '';
      // return ['result'=>$bills];
      // dd($year, $location, $code, $info);
      return view('advanced.report.business.business-listing-details', compact('bills', 'year', 'location', 'info', 'wcpScript', 'totalBill', 'electoral'));
    }

    public function apiBusinessListing()
    {
      $data = \App\Bill::with(['business'])->limit('4')->get()->groupBy('electoral_name');
      // $data = \App\Bill::with(['property'])->limit('4')->get()->sortBy('property.electoral.description');
      return response()->json(['data' => $data]);
    }







    /** Feefixing Listings */

    public function feefixingListingSearch()
    {
      return view('advanced.report.feefixing.search');
    }

    public function feefixingListing(Request $request)
    {
        $year = $request->year;
        $account = $request->account;
        // dd($request->all());
        if($account == 'property'):
          if($year == date('Y')):
            $feefixing = \App\PropertyType::with('categories')->orderBy('code', 'asc')->get();
          else:
            $feefixing = \App\PropertyType::with(['fixcategories' => function($query) use ($year) {
              $query->where('year', $year);
            }])->orderBy('code', 'asc')->get();
          endif;
          return view('advanced.report.feefixing.listing', compact('feefixing', 'year', 'account'));
        endif;
        if($account == 'business'):
          if($year == date('Y')):
            $feefixing = \App\BusinessType::with('categories')->orderBy('code', 'asc')->get();
          else:
            $feefixing = \App\BusinessType::with(['fixcategories' => function($query) use ($year) {
              $query->where('year', $year);
            }])->orderBy('code', 'asc')->get();
          endif;

          return view('advanced.report.feefixing.listing', compact('feefixing', 'year', 'account'));
        endif;

        return redirect()->back();
    }







    public function paginate($items, $perPage = 15, $page = null, $baseUrl = null, $options = [])
    {

      $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
      $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
      $lap = new \Illuminate\Pagination\LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

      if ($baseUrl) {
            $lap->setPath($baseUrl);
        }

        return $lap;
    }


}
