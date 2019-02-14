<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendSMS;
use App\Jobs\SendCustomSMS;

use config;

class SetupController extends Controller
{

  function __construct()
  {
      $this->middleware('auth');
  }

  protected static $smsBaseURL = 'https://mysms.nsano.com/api/v1/sms/single';


    public function sms()
    {
      $properties = session()->get('properties');
      $zonal_id = "";
      if(!$properties) $properties = \App\Property::with(['owner', 'zonal'])->orderBy('property_no', 'asc')->paginate(100);
      // dd($properties);
      return view('console.setups.sms', compact('properties', 'zonal_id'));
    }
    public function bsms()
    {
      $businesses = session()->get('businesses');
      $zonal_id = "";
      if(!$businesses) $businesses = \App\Business::with(['owner', 'zonal'])->orderBy('business_no', 'asc')->paginate(100);
      // dd($properties);
      return view('console.setups.business-sms', compact('businesses', 'zonal_id'));
    }

    public function propertyFilterSMS(Request $request)
    {
      $properties = \App\Property::with(['owner', 'zonal'])->where('zonal_id', $request->zonal)->orderBy('property_no', 'asc')->get();
      if($properties->count() == 0) return redirect()->back();
      // dd($request->zonal);
      return redirect()->route('setups.sms')->with(['properties' => $properties, 'zonal_id' => $request->zonal]);
    }

    //
    // $mobile = '233' . $mobile;
    // $message = 'Dear ' . $property->owner->name . ' of PROPERTY ACC No: '. $property->property_no . ' has been successfully registered with ' .env('ASSEMBLY_SMS_FROM').' Assembly. For any enquiry, please contact us.' ;
    // $smsRes = $this->sendSms($mobile, $message);

    // We are happy to welcome you to SODA as a stakeholder of the Assembly and in case of any enquiry you can contact the client unit or information desk. Please feel free to walk to the Assembly because we are here to serve you.

    public function sendNewSMS1(Request $request)
    {
      if($request->zonal) dd('op');
      dd($request->all());

      SendSMS::dispatch($request->all());

      return redirect()->route('processing');
    }

    public function sendBillsSMS(Request $request)
    {
      SendCustomSMS::dispatch();
      return redirect()->route('processing');
    }

    public static function sendSms($to, $text)
    {
      // dd(config('app.smsBaseURL'), config('app.smsAPIKey'), env('ASSEMBLY_SMS_FROM'));
      $data = [ "sender" => env('ASSEMBLY_SMS_FROM'), "recipient" => $to, "message" => $text];
      $client = new \GuzzleHttp\Client([ 'headers' => ['Content-Type' => 'application/json', 'X-SMS-Apikey' => config('app.smsAPIKey')]]);
      $res = $client->request('POST', config('app.smsBaseURL'), ['json' => $data, 'verify' => false]);
      $response = json_decode($res->getBody());
      // dd($response);

      // if($response->messages[0]->status->groupName == "REJECTED") return 'bad';
      return 'good';
    }
}
