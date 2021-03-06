<?php

namespace App\Reports;

use App\Reports\BusinessReport as Business;

class ElectoralBusinessReport extends Model
{
	protected $table = 'electorals';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $year = '2018';

    public function withRelation($relations, $year)
    {
        // dd($year)
        return $this->with($relations);
    }

    public function properties()
    {
      return $this->hasMany('App\Property','electoral_id');
    }
    public function businesses()
    {
      return $this->hasMany('App\Business','electoral_id');
		}
		public function bills()
    {
      return $this->hasMany('App\Bill','electoral_id');
    }


    // public function getCountBillsAttribute()
    // {
		//
    //     $year = $this->year;
    // 	$props = Business::where('electoral_id',$this->code)->with(['bills' => function($query) use ($year) {
    //         $query->where('year', $year);
    //     }])->get();
    // 	$total = 0;
		//
    // 	foreach ($props as $key => $value) {
    // 		$total = $total + $value->bills->count();
    // 	}
		//
    //     // dd($total);
		//
    // 	return $total;
		//
    // }
    // public function countBillsEl($year)
    // {
    // 	$props = Business::where('electoral_id',$this->code)->with(['bills' => function($query) use ($year) {
    // 		$query->where('year', $year);
    // 	}])->get();
    // 	$total = 0;
		//
    // 	foreach ($props as $key => $value) {
    // 		$total = $total + $value->bills->count();
    // 	}
		//
    // 	return $total;
		//
    // }
    // public function getBillsArrearsAttribute()
    // {
    // 	 $year = $this->year;
    //     $props = Business::where('electoral_id',$this->code)->with(['bills' => function($query) use ($year) {
    //         $query->where('year', $year);
    //     }])->get();
    //     $year = $this->year;
    //     $props = Business::where('electoral_id',$this->code)->with(['bills' => function($query) use ($year) {
    //         $query->where('year', $year);
    //     }])->get();
    // 	$sum = 0.0;
		//
    // 	foreach ($props as $value) {
    // 		$sum = $sum + $value->billArrears();
    // 	}
		//
    // 	return $sum;
    // }
    // public function getCurrentBillsAttribute()
    // {
    // 	 $year = $this->year;
    //     $props = Business::where('electoral_id',$this->code)->with(['bills' => function($query) use ($year) {
    //         $query->where('year', $year);
    //     }])->get();
    // 	$sum = 0.0;
		//
    // 	foreach ($props as $value) {
    // 		$sum = $sum + $value->currentBills();
    // 	}
		//
    // 	return $sum;
    // }
    // public function getTotalPaidBillsAttribute()
    // {
    // 	$year = $this->year;
    //     $props = Business::where('electoral_id',$this->code)->with(['bills' => function($query) use ($year) {
    //         $query->where('year', $year);
    //     }])->get();
    // 	$sum = 0.0;
		//
    // 	foreach ($props as $value) {
    // 		$sum = $sum + $value->totalPaid();
    // 	}
		//
    // 	return $sum;
    // }
    // public function getBillsArrayAttribute()
    // {
    // 	// $props = Property::where('electoral_id',$this->code)->has('bills')->with('bills')->get();
    //     $year = $this->year;
    //     $props = Business::where('electoral_id',$this->code)->with(['bills' => function($query) use ($year) {
    //         $query->where('year', $year);
    //     }])->get();
		//
    // 	$array = [];
		//
    // 	foreach ($props as $value) {
    //         if(count($value->bills) > 0):
    //     		$value->billArray()->owner = $value->owner ? ($value->owner->name ?: 'no name') : ('no name');
    //     		$value->billArray()->address = $value->owner ? ($value->owner->address ?: 'NA') : ('no address');
    //     		$value->billArray()->category = $value->category ? ($value->category->code ?: $value->category->code) : ('no cat');
    //             $value->billArray()->store = $value->store_number ?: 'NA';
    //     		array_push($array, $value->billArray());
    //         endif;
    // 	}
		//
    // 	return $array;
    // }

}
