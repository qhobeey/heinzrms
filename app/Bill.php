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

    public function properties()
    {
      return $this->hasMany(Property::class, 'property_no', 'account_no');
    }
    public function businesses()
    {
      return $this->hasMany(Business::class, 'business_no', 'account_no');
    }
    public function getOutstndAttribute()
    {
      return ($this->arrears + $this->current_amount) - $this->total_paid;
    }
    public function getTbillAttribute()
    {
      return ($this->arrears + $this->current_amount);
    }
    public function getArrearsAttribute()
    {
      $bool = ($this->adjust_arrears == null) ? $this->arrears : floatval($this->adjust_arrears);
      return $bool;
    }
}
