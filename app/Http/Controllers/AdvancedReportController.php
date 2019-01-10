<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Location\Zonal;
use App\Models\Location\Electoral;

use App\Property;
use App\Bill;

use DB;
use URL;

// use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class AdvancedReportController extends Controller
{


    public function __construct(Request $request, Zonal $zonal, Property $property, Electoral $electoral, Bill $bill)
    {
      $this->zonal = $zonal;
      $this->property = $property;
      $this->electoral = $electoral;
      $this->bill = $bill;
      $this->request = $request;

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
             $electorals = $this->electoral->with(['properties.bills'])->withCount('properties')->paginate(10);
             $location = $query['location'];
             $year = $query['year'];

         // return ['result'=>$electorals];

         return view('advanced.report.property.property-listing', compact('electorals', 'location', 'year'));
       endif;
       if($request->loc != "a") return $this->propertySearchListingDetails($request->all());

       $location = $request['location'];
       $year = $request['year'];

       $electorals = $this->electoral->with(['properties.bills'])->withCount('properties')->paginate(10)->appends(request()->query());
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
      
      $electoral = $this->electoral->with(['properties.bills']);
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
      $electoral = $this->electoral->with(['properties.bills'])->where('code', $query)->withCount('properties')->first();
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
