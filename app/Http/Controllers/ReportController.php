<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Property;

class ReportController extends Controller
{

    function __construct()
    {
        $this->middleware('auth');
    }

    public function propertyIndex()
    {
      $properties = Property::with(['type', 'owner', 'category'])->latest()->paginate(30);
      return view('console.reports.property-report', compact('properties'));
    }

    public function generateProperties(Request $request)
    {
      // dd($request->all());
      $todaysdate = date("Y-m-d");
      // dd($todaysdate);
      $properties = Property::with(['type', 'owner', 'category'])->latest();

      if ($request->has('property_type') && $request->input('property_type') != null) {
        $properties->where('property_type', $request->input('property_type'));
      }
      if ($request->has('property_category') && $request->input('property_category') != null) {
        $properties->where('property_category', $request->input('property_category'));
      }
      if ($request->has('property_owner') && $request->input('property_owner') != null) {
        $properties->where('property_owner', $request->input('property_owner'));
      }
      if ($request->has('starting') && $request->input('starting') != null) {
        if ($request->has('ending') && $request->input('ending') != null) {
          $properties->whereBetween('created_at', [$request->input('starting'), $request->input('ending')]);
        }else{
          $properties->whereBetween('created_at', [$request->input('starting'), $todaysdate]);
        }
      }
      if ($request->has('collector') && $request->input('collector') != null) {
        $properties->where('client', $request->input('collector'));
      }
      // $property->get();
      // dd($property->get());
      return view('console.reports.index', ['properties' => $properties->get()]);
    }











}
