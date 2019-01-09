<?php

namespace App;

class Business extends Model
{
  public function type()
  {
      return $this->belongsTo('App\BusinessType', 'business_type', 'code');
  }
  public function category()
  {
      return $this->belongsTo('App\BusinessCategory', 'business_category', 'code');
  }
  public function owner()
  {
      return $this->belongsTo('App\BusinessOwner', 'business_owner', 'owner_id')
      ->select(['owner_id', 'name', 'phone']);
  }
  public function zonal()
  {
      return $this->belongsTo('App\Models\Location\Zonal', 'zonal_id', 'code');
  }
  public function tas()
  {
      return $this->belongsTo('App\Models\Location\Ta', 'tas_id', 'code');
  }

}
