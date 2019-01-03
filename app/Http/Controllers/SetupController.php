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
      // dd($request->all());

      SendSMS::dispatch($request->all());

      return redirect()->route('processing');
    }

    public function sendBillsSMS(Request $request)
    {
      // $bill = \App\Bill::with('property')->where('bill_type', 'p')->first();
      // $bills = \App\Bill::with('property')->where('bill_type', 'p')->latest()->get();
      // foreach($bills as $bill):
      //   if(!$bill->property->owner || $bill->property->owner->phone == "") continue;
      //   $number = $bill->property->owner->phone;
      //   if($number[0] == '0') $number = ltrim($number, '0');
      //   $number = substr($number, 0, 9);
      //   if($number == "NULL" || $number == "") continue;
      //   $number = '233' . $number;
      //   $acc_type = ($bill->bill_type == 'p') ? 'property' : 'business';
      //   $message = "Dear ".$bill->property->owner->name. " of " .$acc_type. " number " .$bill->account_no. ". we hereby notify you of your " .$acc_type. " Rate for the year " .$bill->year. " is GHC " .$bill->current_amount. ". we will appreciate it if you could pay the said amount to the revenue collector close to your locality or pay at the revenue office, for any enquiries please contact the Revenue Head on 0549825660";
      //   // dump($number);
      //   $smsRes = self::sendSms($number, $message);
      //   if($smsRes == 'good'){
      //     \App\InstantSMS::create(['phone' => $number, 'sent' => 1, 'failed' => 0, 'message' => $message]);
      //   }else{
      //     \App\InstantSMS::create(['phone' => $number, 'sent' => 0, 'failed' => 1, 'message' => $message]);
      //   }
      // endforeach;
      // dd('ok');
      SendCustomSMS::dispatch();
      return redirect()->route('processing');
    }

    public static function sendSms($to, $text)
    {
      $data = [ "sender" => env('ASSEMBLY_SMS_FROM'), "recipient" => $to, "message" => $text];
      $client = new \GuzzleHttp\Client([ 'headers' => ['Content-Type' => 'application/json', 'X-SMS-Apikey' => config('app.smsBaseURL')]]);
      $res = $client->request('POST', config('app.smsBaseURL'), ['json' => $data]);
      $response = json_decode($res->getBody());

      // if($response->messages[0]->status->groupName == "REJECTED") return 'bad';
      return 'good';
    }
}
