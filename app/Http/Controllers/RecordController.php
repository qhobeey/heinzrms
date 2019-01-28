<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;
use App\Bill;
use App\Setting;
use App\Payment;
use Carbon\Carbon;

class RecordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getBills()
    {

        $bills = Bill::with('property')->orderBy('account_no', 'asc')->get();
        // dd($type);
        // dd($property);
        // if($year = request('year')):
        //     $properties->whereYear('created_at', $year)->get();
        //     dd($properties);
        // endif;
        // $properties->get();
        // dd($bills);
        return view('console.property.records.bills', compact('bills'));
    }
    public function getPaymentsProperty()
    {
        // $bills = \App\Bill::latest()->get();
        return view('console.property.payments.payment');
    }
    public function getpaymentsBusiness()
    {
        // $bills = \App\Bill::latest()->get();
        return view('console.business.payments.payment');
    }
    public function savePayments(Request $request)
    {
        $payment = $request->validate([
            'property_id' => 'required',
            'collector_id' => 'required',
            'cashier_id' => 'required',
            'amount_paid' => 'required',
            'payment_mode' => 'required',
            'gcr_number' => 'required',
            'payment_date' => 'required',
            'cprn' => 'required',
        ]);

        $payment = array_merge($payment, ['payment_year' => Carbon::now()->year,
            'payment_type' => 'P', 'account_no' => 'empty',
            'amount_paid' => floatval($payment['amount_paid'])
        ]);

        $truesave = Payment::create($payment);
        $bill = Bill::where('property_id', $truesave['property_id'])->first();
        $c_amt_paid = $truesave['amount_paid'];
        $o_amt_paid = $bill->current_amount;
        $a_amount = $o_amt_paid - $c_amt_paid;
        // add column to database and set to full payment if current - new == 0
        $bill->update(['current_amount' => $a_amount]);

        return redirect()->route('property.payments.payment');
    }

    public function previewBill($query)
    {
        $bill = Bill::with('property')->where('id', $query)->first();
        $setting = Setting::latest()->first();
        // dd($bill);
        $print_data = [
            'account_no' => $bill->account_no, 'name' => $bill->property->owner->name,
            'address' => $bill->property->address, 'property_type' => $bill->property->type->description,
            'property_category' => $bill->property->category->description, 'rateable_value' => $bill->rateable_value,
            'rate_imposed' => $bill->rate_pa, 'bill_year' => $bill->year, 'sub_metro' => $bill->property->zonal->description, 'tas' => $bill->property->tas->description,
            'electoral' => $bill->property->electoral->description, 'street' => 'unknown', 'previous_year' => 0.0, 'amount_paid' => 0.0, 'arrears' => $bill->arrears,
            'current_fees' => $bill->current_amount, 'total_amount' => $bill->total_payment
        ];
        // dd($print_data);
        // dd($bill->property->owner->name);
        return view('console.property.records.preview-bills', compact('print_data', 'setting'));
    }

}
