<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Reports\ReportListing as Report;

use App\Models\Location\Zonal;
use App\Models\Location\Electoral;
use App\Models\Location\Community;

use App\Reports\ElectoralPropertyReport;
use App\Reports\ElectoralBusinessReport;

use App\Reports\ZonalPropertyReport;
// use App\Reports\ElectoralBusinessReport;

use App\Reports\CommunityPropertyReport;
use App\Reports\CommunityBusinessReport;

use App\Reports\PropertyReport;
use App\Reports\BusinessReport;

use App\Property;
use App\Bill;

use DB;
use URL;
use File;
use Response;

use Illuminate\Support\Facades\Storage;
use Excel;
use App\Exports\NorminalRowExportProperty;

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

use App\Jobs\PreparePropertyExport;
use App\Jobs\PrepareBusinessExport;

class AdvancedReportController extends Controller
{


    public function __construct(
        Request $request,
        Zonal $zonal,
        PropertyReport $property,
        ElectoralPropertyReport $electoralProperty,
        ElectoralBusinessReport $electoralBusiness,
        Bill $bill,
        BusinessReport $business,
        ZonalPropertyReport $zonalProperty,
        CommunityPropertyReport $communityProperty,
        CommunityBusinessReport $communityBusiness
    ) {
        $this->zonal = $zonal;
        $this->property = $property;
        $this->electoralProperty = $electoralProperty;
        $this->electoralBusiness = $electoralBusiness;
        $this->zonalProperty = $zonalProperty;
        $this->communityProperty = $communityProperty;
        $this->communityBusiness = $communityBusiness;
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
        return ['result' => $test];
    }

    public function propertyListingSearch()
    {
        return view('advanced.report.property.search-board');
    }

    public function propertyListing(Request $request)
    {

        // dd($request->all());
        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());
        if ($request->loc != "a") return $this->propertySearchListingDetails($request->all());

        $location = $request['location'];
        $year = $request['year'];

        switch ($location) {
            case 'electoral':
                $electorals = $this->electoralProperty->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'));
                }])->paginate(50)->appends(request()->query());
                $elects = $this->electoralProperty->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
                }])->get();
                return view('advanced.report.property.property-listing', compact('electorals', 'location', 'year', 'wcpScript'));
                break;
            case 'zonal':
                $zonals = $this->zonalProperty->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'));
                }])->paginate(50)->appends(request()->query());
                $elects = $this->zonalProperty->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
                }])->get();
                return view('advanced.report.property.property-listing-zonal', compact('zonals', 'location', 'year', 'wcpScript'));
                break;
            case 'community':
                $communities = $this->communityProperty->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'));
                }])->paginate(50)->appends(request()->query());
                $elects = $this->communityProperty->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
                }])->get();

                return view('advanced.report.property.property-listing-community', compact('communities', 'location', 'year', 'wcpScript'));
                break;

            default:
                return redirect()->back;
                break;
        }

        // return ['result'=>$elects->bills];



    }

    public function propertySearchListingDetails($data)
    {
        // dd($data);

        $location = '';
        $year = '';
        $loc = '';
        if (array_key_exists('location', $data)) {
            // dd('o');
            $location = $data['location'] ?: '';
            $url = url('/') . '/console/advanced/report/property/' . $data['location'] . '/' . $data['loc'] . '/' . $data['year'] . '/';
        } else {
            $parts = parse_url(URL::previous());
            parse_str($parts['query'], $query);
            $url = url('/') . '/console/advanced/report/property/' . $query['location'] . '/' . $query['loc'] . '/' . $query['year'] . '/';
            // dd($url);
        }
        if (array_key_exists('year', $data)) {
            $year = $data['year'] ?: '';
        }
        if (array_key_exists('loc', $data)) {
            $loc = $data['loc'] ?: '';
        }

        // dd($loc);

        // $electoral = $this->electoralProperty->where('code', $loc)->get();
        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());

        switch ($location) {
            case 'electoral':
                $electoral = $this->electoralProperty->where('code', $loc)->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
                }])->first();
                $bills = $electoral ? $this->paginate($electoral->bills, $perPage = 30, $page = null, $baseUrl = $url, $options = []) : [];
                $info = $electoral ? $electoral->description : '';
                $totalBill = $electoral ? $electoral->bills->count() : '';
                $code = $loc;
                // dd($electoral->bills->count());

                // return ['result'=>$bills];
                return view('advanced.report.property.property-listing-details', compact('bills', 'year', 'location', 'wcpScript', 'info', 'totalBill', 'electoral', 'code'));
                break;
            case 'zonal':
                $zonal = $this->zonalProperty->where('code', $loc)->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
                }])->first();
                $bills = $zonal ? $this->paginate($zonal->bills, $perPage = 30, $page = null, $baseUrl = $url, $options = []) : [];
                $info = $zonal ? $zonal->description : '';
                $totalBill = $zonal ? $zonal->bills->count() : '';
                $code = $loc;
                return view('advanced.report.property.property-listing-details-zonal', compact('bills', 'year', 'location', 'wcpScript', 'info', 'totalBill', 'zonal', 'code'));
                break;
            case 'community':
                $community = $this->communityProperty->where('code', $loc)->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
                }])->first();
                $bills = $community ? $this->paginate($community->bills, $perPage = 30, $page = null, $baseUrl = $url, $options = []) : [];
                $info = $community ? $community->description : '';
                $totalBill = $community ? $community->bills->count() : '';
                $code = $loc;
                return view('advanced.report.property.property-listing-details-zonal', compact('bills', 'year', 'location', 'wcpScript', 'info', 'totalBill', 'community', 'code'));
                break;
            case 'type':
                dd('type');
                $type = $this->zonalProperty->where('code', $loc)->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
                }])->first();
                $bills = $zonal ? $this->paginate($zonal->bills, $perPage = 30, $page = null, $baseUrl = $url, $options = []) : [];
                $info = $zonal ? $zonal->description : '';
                $totalBill = $zonal ? $zonal->bills->count() : '';
                $code = $loc;
                return view('advanced.report.property.property-listing-details-zonal', compact('bills', 'year', 'location', 'wcpScript', 'info', 'totalBill', 'zonal', 'code'));
                break;

            default:
                return redirect()->back;
                break;
        }
    }





    public function propertyListingDetails(Request $request, $location, $code, $year)
    {
        // dd($location, $code, $year);

        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());

        switch ($location) {
            case 'electoral':
                $electoral = $this->electoralProperty->where('code', $code)->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->with('properties')->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
                }])->first();

                $bills = $electoral ? $this->paginate($electoral->bills, $perPage = 30, $page = null, $baseUrl = $request->url() . '/', $options = []) : [];
                $info = $electoral ? $electoral->description : '';
                $totalBill = $electoral ? $electoral->bills->count() : '';
                // return ['result'=> $bills->currentPage()];
                // dd($year, $location, $code, $info);
                return view('advanced.report.property.property-listing-details', compact('bills', 'year', 'location', 'info', 'wcpScript', 'totalBill', 'electoral', 'code'));
                break;
            case 'zonal':
                $zonal = $this->zonalProperty->where('code', $code)->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->with('properties')->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
                }])->first();

                $bills = $zonal ? $this->paginate($zonal->bills, $perPage = 30, $page = null, $baseUrl = $request->url() . '/', $options = []) : [];
                $info = $zonal ? $zonal->description : '';
                $totalBill = $zonal ? $zonal->bills->count() : '';
                // return ['result'=> $bills->currentPage()];
                // dd($year, $location, $code, $info);
                return view('advanced.report.property.property-listing-details-zonal', compact('bills', 'year', 'location', 'info', 'wcpScript', 'totalBill', 'zonal', 'code'));
                break;
            case 'community':
                $community = $this->communityProperty->where('code', $code)->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->with('properties')->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
                }])->first();

                $bills = $community ? $this->paginate($community->bills, $perPage = 30, $page = null, $baseUrl = $request->url() . '/', $options = []) : [];
                $info = $community ? $community->description : '';
                $totalBill = $community ? $community->bills->count() : '';
                // return ['result'=> $bills->currentPage()];
                // dd($year, $location, $code, $info);
                return view('advanced.report.property.property-listing-details-community', compact('bills', 'year', 'location', 'info', 'wcpScript', 'totalBill', 'community', 'code'));
                break;

            default:
                return redirect()->back;
                break;
        }
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


    public function exportProperty(Request $request, $year, $electoral, $type = 'electorals')
    {
        switch ($type) {
            case 'electorals':
                $elct = Electoral::where('code', $electoral)->first();
                // $export = new NorminalRowExportProperty(2019, '1401');
                $name = strtoupper(str_slug($elct->description) . '-property-norminal-row-' . $year) . '.xlsx';
                PreparePropertyExport::dispatch($year, $electoral, $name, $type);
                break;
            case 'communities':
                $elct = Community::where('code', $electoral)->first();
                // $export = new NorminalRowExportProperty(2019, '1401');
                $name = strtoupper(str_slug($elct->description) . '-property-norminal-row-' . $year) . '.xlsx';
                PreparePropertyExport::dispatch($year, $electoral, $name, $type);
                break;
            case 'zonals':
                $elct = Zonal::where('code', $electoral)->first();
                // $export = new NorminalRowExportProperty(2019, '1401');
                $name = strtoupper(str_slug($elct->description) . '-property-norminal-row-' . $year) . '.xlsx';
                PreparePropertyExport::dispatch($year, $electoral, $name, $type);
                break;

            default:
                // code...
                break;
        }

        return redirect()->back();
    }

    public function exportBusiness(Request $request, $year, $electoral)
    {
        // dd($year, $electoral);
        $elct = Electoral::where('code', $electoral)->first();
        // $export = new NorminalRowExportProperty(2019, '1401');
        $name = strtoupper(str_slug($elct->description) . '-business-norminal-row-' . $year) . '.xlsx';
        PrepareBusinessExport::dispatch($year, $electoral, $name);
        return redirect()->back();
    }

    public function downloadLink()
    {
        $data = \App\TemporalFiles::first();
        $data->available = 0;
        $data->save();
        // dd($data);
        $page = File::put('images/kbills/' . $data->filename, $data->file);
        return Response::download(public_path('images/kbills/' . $data->filename));
    }

    public function checkLinkAvailable()
    {
        $response;
        $data = \App\TemporalFiles::first();
        if ($data) {
            if ($data->available == 1) {
                $response = 'success';
            } else {
                $response = 'failed';
            }
        } else {
            $response = 'none';
        }

        return response()->json(['status' => $response]);
    }







    /** Business Listings */

    public function businessListingSearch()
    {
        return view('advanced.report.business.search-board');
    }



    public function businessListing(Request $request)
    {

        // dd($request->all());

        if ($request->loc != "a") return $this->businessSearchListingDetails($request->all());

        $location = $request['location'];
        $year = $request['year'];


        // return ['result'=>$electorals];
        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());

        switch ($location) {
            case 'electoral':
                $electorals = $this->electoralBusiness->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('b'))->orderBy('account_no', 'asc');
                }])->paginate(10)->appends(request()->query());
                return view('advanced.report.business.business-listing', compact('electorals', 'location', 'year', 'wcpScript'));
            case 'community':
                // dd('ok');
                $communities = $this->communityBusiness->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('b'))->orderBy('account_no', 'asc');
                }])->paginate(10)->appends(request()->query());

                return view('advanced.report.business.business-listing-communities', compact('communities', 'location', 'year', 'wcpScript'));

                break;

            default:
                return redirect()->back;
                break;
        }

        return false;
    }

    public function businessSearchListingDetails($data)
    {
        // dd($data);

        $location = '';
        $year = '';
        $loc = '';
        if (array_key_exists('location', $data)) {
            // dd('o');
            $location = $data['location'] ?: '';
            $url = url('/') . '/console/advanced/report/business/' . $data['location'] . '/' . $data['loc'] . '/' . $data['year'] . '/';
        } else {
            $parts = parse_url(URL::previous());
            parse_str($parts['query'], $query);
            $url = url('/') . '/console/advanced/report/business/' . $query['location'] . '/' . $query['loc'] . '/' . $query['year'] . '/';
            // dd($url);
        }
        if (array_key_exists('year', $data)) {
            $year = $data['year'] ?: '';
        }
        if (array_key_exists('loc', $data)) {
            $loc = $data['loc'] ?: '';
        }

        // dd($loc);

        // $electoral = $this->electoralProperty->where('code', $loc)->get();
        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());
        $electoral = $this->electoralBusiness->where('code', $loc)->whereHas('bills', function ($q) use ($year) {
            $q->where('year', $year);
        })->with(['bills' => function ($query) use ($year) {
            $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('b'))->orderBy('account_no', 'asc');
        }])->first();
        $bills = $electoral ? $this->paginate($electoral->bills, $perPage = 30, $page = null, $baseUrl = $url, $options = []) : [];
        $info = $electoral ? $electoral->description : '';
        $totalBill = $electoral ? $electoral->bills->count() : '';
        $code = $loc;
        // dd($electoral->bills->count());

        // return ['result'=>$bills];
        return view('advanced.report.business.business-listing-details', compact('bills', 'year', 'location', 'wcpScript', 'info', 'totalBill', 'electoral', 'code'));
    }





    public function businessListingDetails(Request $request, $location, $code, $year)
    {
        // dd($code);
        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());


        switch ($location) {
            case 'electoral':
                $electoral = $this->electoralBusiness->where('code', $code)->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->with('businesses')->where('year', $year)->where(strtoupper('bill_type'), strtoupper('b'))->orderBy('account_no', 'asc');
                }])->first();

                $bills = $electoral ? $this->paginate($electoral->bills, $perPage = 20, $page = null, $baseUrl = $request->url() . '/', $options = []) : [];
                $info = $electoral ? $electoral->description : '';
                $totalBill = $electoral ? $electoral->bills->count() : '';
                return view('advanced.report.business.business-listing-details', compact('bills', 'year', 'location', 'info', 'wcpScript', 'totalBill', 'electoral', 'code'));
                break;
            case 'community':
                $electoral = $this->communityBusiness->where('code', $code)->whereHas('bills', function ($q) use ($year) {
                    $q->where('year', $year);
                })->with(['bills' => function ($query) use ($year) {
                    $query->with('businesses')->where('year', $year)->where(strtoupper('bill_type'), strtoupper('b'))->orderBy('account_no', 'asc');
                }])->first();

                $bills = $electoral ? $this->paginate($electoral->bills, $perPage = 20, $page = null, $baseUrl = $request->url() . '/', $options = []) : [];
                $info = $electoral ? $electoral->description : '';
                $totalBill = $electoral ? $electoral->bills->count() : '';
                // dd($bills);
                return view('advanced.report.business.business-listing-details', compact('bills', 'year', 'location', 'info', 'wcpScript', 'totalBill', 'electoral', 'code'));
                break;

            default:
                return redirect()->back;
                break;
        }

        return view('advanced.report.business.business-listing-details', compact('bills', 'year', 'location', 'info', 'wcpScript', 'totalBill', 'electoral', 'code'));
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
        if ($account == 'property') :
            if ($year == date('Y')) :
                $feefixing = \App\PropertyType::with('categories')->orderBy('code', 'asc')->get();
            else :
                $feefixing = \App\PropertyType::with(['fixcategories' => function ($query) use ($year) {
                    $query->where('year', $year);
                }])->orderBy('code', 'asc')->get();
            endif;
            return view('advanced.report.feefixing.listing', compact('feefixing', 'year', 'account'));
        endif;
        if ($account == 'business') :
            if ($year == date('Y')) :
                $feefixing = \App\BusinessType::with('categories')->orderBy('code', 'asc')->get();
            else :
                $feefixing = \App\BusinessType::with(['fixcategories' => function ($query) use ($year) {
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

    public function defaultersReport()
    {
        return view('advanced.report.defaulters.index');
    }

    public function defaultersReportPost(Request $request)
    {
        $this->propertyDefaulters($request->all());
    }

    private function propertyDefaulters($request)
    {
        dd($request['bill_year']);
        $year = $request['bill_year'];
        $operator = $request['operator'];
        $amount = $request['amount'];

        $electorals = $this->electoralProperty->with(['bills' => function ($query) use ($year, $operator, $amount) {
            $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->where('arrears', "%{$operator}%", $amount);
        }])->paginate(50);
        // $elects = $this->electoralProperty->with(['bills'=>function($query) use ($year) {
        //    $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
        // }])->get();
        return ['result' => $electorals];
        // $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintHtmlCardController@printFile'), Session::getId());

        return view('advanced.report.property.property-listing', compact('electorals', 'location', 'year', 'wcpScript'));
    }
}