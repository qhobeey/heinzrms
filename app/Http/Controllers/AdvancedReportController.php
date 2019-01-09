<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Location\Zonal;
use App\Models\Location\Electoral;

use App\Property;
use App\Bill;

use DB;

// use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class AdvancedReportController extends Controller
{


    public function __construct(Zonal $zonal, Property $property, Electoral $electoral, Bill $bill)
    {
      $this->zonal = $zonal;
      $this->property = $property;
      $this->electoral = $electoral;
      $this->bill = $bill;

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

       // dd($request->all());
       if($request->loc != "a") return $this->propertySearchListingDetails($request->all());

       $electorals = $this->electoral->with(['properties.bills'])->withCount('properties')->paginate(10);
       $location = $request['location'];
       $year = $request['year'];

      // return ['result'=>$electorals];

      return view('advanced.report.property.property-listing', compact('electorals', 'location', 'year'));
    }

    public function propertySearchListingDetails($data)
    {
      $location = $data['location'];
      $year = $data['year'];
      // dd($location, $year);
      $url = url('/').'/console/advanced/report/property/'.$data['location'].'/'.$data['loc'];
      $electoral = $this->electoral->with(['properties.bills'])->where('code', $data['loc'])->withCount('properties')->first();
      $bills = $this->paginate($electoral->bills_array, $perPage = 50, $page = null, $baseUrl = $url.'/', $options = []);

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
