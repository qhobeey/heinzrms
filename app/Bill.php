<?php

namespace App;

class Bill extends Model
{

    protected $table = 'bills';
    // protected $primaryKey = 'account_no';
    // protected $keyType = 'string';
    // public $incrementing = false;

    public function property()
    {
        return $this->belongsTo(Property::class, 'account_no', 'property_no');
    }
    public function business()
    {
        return $this->belongsTo(Business::class, 'account_no', 'business_no');
    }
}
