<?php

namespace App\Models\Location;

use App\Property;

class Electoral extends Model
{
	protected $table = 'electorals';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    public $filterKey = '';

    protected $appends = ['count_bills', 'bills_arrears', 'current_bills', 'total_paid_bills', 'bills_array', 'blabla'];

    public function properties()
    {
    	// dd($this->query);
      return $this->hasMany('App\Property','electoral_id');
    }
    public function getCountBillsAttribute()
    {
    	$props = Property::where('electoral_id',$this->code)->with('bills')->get();
    	$total = 0;

    	foreach ($props as $key => $value) {
    		$total = $total + $value->bills->count();
    	}

    	return $total;
    	
    }
    public function getBillsArrearsAttribute()
    {
    	$props = Property::where('electoral_id',$this->code)->with('bills')->get();
    	$sum = 0.0;

    	foreach ($props as $value) {
    		$sum = $sum + $value->billArrears();
    	}

    	return $sum;
    }
    public function getCurrentBillsAttribute()
    {
    	$props = Property::where('electoral_id',$this->code)->with('bills')->get();
    	$sum = 0.0;

    	foreach ($props as $value) {
    		$sum = $sum + $value->currentBills();
    	}

    	return $sum;
    }
    public function getTotalPaidBillsAttribute()
    {
    	$props = Property::where('electoral_id',$this->code)->with('bills')->get();
    	$sum = 0.0;

    	foreach ($props as $value) {
    		$sum = $sum + $value->totalPaid();
    	}

    	return $sum;
    }
    public function getBillsArrayAttribute()
    {
    	// dd($this->filterKey);
    	$props = Property::where('electoral_id',$this->code)->has('bills')->with('bills')->get();
    	$array = [];

    	foreach ($props as $value) {
    		$value->billArray()->owner = $value->owner ? ($value->owner->name ?: 'no name') : ('no name');
    		$value->billArray()->address = $value->owner ? ($value->owner->address ?: 'NA') : ('no address');
    		$value->billArray()->category = $value->category ? ($value->category->code ?: $value->category->code) : ('no cat');
    		array_push($array, $value->billArray());
    		// dump($value->billArray());
    	}

    	return $array;
    }
    public static function blabla($value)
    {
    	return $this->electorals;
    }

 //    public function scopeStatus (Builder $query, $name) {
	//     return $query->whereHas('status', function ($q) use ($name) {
	//             $q->where('name', $name);
	//     });
	// }

    //define the inverse relation for zonal here
    // public function zonals()
    // {
    // 	return $this->belongsTo('App\Models\Location\Zonal', 'zonal_id', 'code');
    // }

}
