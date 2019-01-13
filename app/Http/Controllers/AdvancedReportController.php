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

      //$electorals = $this->electoral->with(['properties','properties.bills'])->withCount('properties')->paginate(5);

       // dd($request->page);
      // http://localhost:8000/console/advanced/report/property?page=1
       if($request->has('page')):
             $parts = parse_url(URL::previous());
          // dd($parts);
             parse_str($parts['query'], $query);
             $electorals = $this->electoralProperty->with(['properties.bills'])->withCount('properties')->paginate(10);
             $location = $query['location'];
             $year = $query['year'];

         // return ['result'=>$electorals];

         return view('advanced.report.property.property-listing', compact('electorals', 'location', 'year'));
       endif;
       if($request->loc != "a") return $this->propertySearchListingDetails($request->all());

       $location = $request['location'];
       $year = $request['year'];

       // $electorals = \App\Bill::where('year', $year)->get();

       // dd($loc);

       // ElectoralReport::setYear('2018');
       // $electorals = $this->property->with(['bills' => function($query) {
       //    $query->where('year', '2018');
       // }])->paginate(5);
       // dd($electorals);

       // $electorals = DB::select("
       //    SELECT code, description, property_no, property_category, property_type, electoral_id, current_amount, arrears, total_paid, year FROM  electorals INNER JOIN (SELECT properties.property_no, properties.property_category,properties.property_type, properties.electoral_id, bills.current_amount, bills.arrears, bills.total_paid, bills.year FROM properties, bills where properties.property_no = bills.account_no AND bills.year = 2018) tbl_properties
       //      ON tbl_properties.electoral_id = electorals.code limit 5
       //  ")->paginate(5);

       // $electorals = DB::table('electorals')
       //  ->join('properties', 'electorals.code', '=', 'properties.electoral_id')
       //  ->join('bills', 'bills.account_no', '=', 'properties.property_no')
       //  ->select('electorals.code', 'properties.property_no', 'properties.property_type', 'properties.property_category', 'properties.electoral_id', 'bills.current_amount', 'bills.arrears', 'bills.total_paid', 'bills.year')->limit(20)->get();

       // return ['result'=>$electorals];

       $electorals = $this->electoralProperty->with(['properties.bills'])->withCount('properties')->paginate(10)->appends(request()->query());
       // dd(intval($year));
       // $electorals = $this->electoral->with(['properties.bills'])->withCount('properties')->paginate(1);
       // $electorals = $this->electoral->retreiveTrack(true)->paginate(1);

       // $electorals = $this->electoral->with(['properties.bills'=>function($query) use ($year) {
       //    $query->where('year', $year);
       // }])->withCount('properties')->paginate(1);
       // dump($year);
       // $electorals = $this->bill->where('year',$year)->first();
       // dd($electorals);
      // return ['result'=>$electorals];

      return view('advanced.report.property.property-listing', compact('electorals', 'location', 'year'));
    }

    public function propertySearchListingDetails($data)
    {
      // dd($data);
      
      $location = '';
      $year = '';
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
      
      // "http://localhost:8000/console/advanced/report/property/electoral/SE002?page=2
      
      // dd($location, $year);
      
      $electoral = $this->electoralProperty->with(['properties.bills']);
      if (array_key_exists('location', $data)) {
        $electoral = $electoral->where('code', $data['loc'])->withCount('properties')->first();
      }else{
        $electoral = $electoral->withCount('properties')->first();
      }

      // dd($url);
      
      $bills = $this->paginate($electoral->bills_array, $perPage = 50, $page = null, $baseUrl = $url, $options = []);

      // return ['result'=>$bills];
      return view('advanced.report.property.property-listing-details', compact('electoral', 'bills', 'year', 'location'));
    }





    public function propertyListingDetails(Request $request, $location, $query, $year)
    {
      // dd($location, $query, $year);
      $electoral = $this->electoralProperty->with(['properties.bills'])->where('code', $query)->withCount('properties')->first();
      $bills = $this->paginate($electoral->bills_array, $perPage = 50, $page = null, $baseUrl = $request->url().'/', $options = []);

      // return ['result'=>$bills];
      return view('advanced.report.property.property-listing-details', compact('electoral', 'bills', 'year', 'location'));
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
       if($request->has('page')):
             $parts = parse_url(URL::previous());
          // dd($parts);
             parse_str($parts['query'], $query);
             $electorals = $this->electoralBusiness->with(['businesses.bills'])->withCount('businesses')->paginate(10);
             $location = $query['location'];
             $year = $query['year'];

         // return ['result'=>$electorals];

         return view('advanced.report.business.business-listing', compact('electorals', 'location', 'year'));
       endif;
       if($request->loc != "a") return $this->businessSearchListingDetails($request->all());

       $location = $request['location'];
       $year = $request['year'];


       $electorals = $this->electoralBusiness->with(['businesses.bills'])->withCount('businesses')->paginate(10)->appends(request()->query());

      return view('advanced.report.business.business-listing', compact('electorals', 'location', 'year'));
    }

    public function businessSearchListingDetails($data)
    {
      // dd($data);
      
      $location = '';
      $year = '';
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
      
      // "http://localhost:8000/console/advanced/report/property/electoral/SE002?page=2
      
      // dd($location, $year);
      
      $electoral = $this->electoralBusiness->with(['businesses.bills']);
      if (array_key_exists('location', $data)) {
        $electoral = $electoral->where('code', $data['loc'])->withCount('businesses')->first();
      }else{
        $electoral = $electoral->withCount('businesses')->first();
      }

      // dd($url);
      
      $bills = $this->paginate($electoral->bills_array, $perPage = 50, $page = null, $baseUrl = $url, $options = []);

      // return ['result'=>$bills];
      return view('advanced.report.business.business-listing-details', compact('electoral', 'bills', 'year', 'location'));
    }





    public function businessListingDetails(Request $request, $location, $query, $year)
    {
      // dd($location, $query, $year);
      $electoral = $this->electoralBusiness->with(['businesses.bills'])->where('code', $query)->withCount('businesses')->first();
      $bills = $this->paginate($electoral->bills_array, $perPage = 50, $page = null, $baseUrl = $request->url().'/', $options = []);

      // return ['result'=>$bills];
      return view('advanced.report.business.business-listing-details', compact('electoral', 'bills', 'year', 'location'));
    }

    public function apiBusinessListing()
    {
      $data = \App\Bill::with(['business'])->limit('4')->get()->groupBy('electoral_name');
      // $data = \App\Bill::with(['property'])->limit('4')->get()->sortBy('property.electoral.description');
      return response()->json(['data' => $data]);
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
