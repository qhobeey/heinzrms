<?php

namespace App;
use App\PropertyOwner;

class Property extends Model
{
    public function type()
    {
        return $this->belongsTo('App\PropertyType', 'property_type', 'code');
    }
    public function category()
    {
        return $this->belongsTo('App\PropertyCategory', 'property_category', 'code');
    }
    public function owner()
    {
        return $this->belongsTo('App\PropertyOwner', 'property_owner', 'owner_id')
        ->select(['owner_id', 'name', 'phone']);
    }

    public function owner_def()
    {
      return PropertyOwner::where('id', $this->property_owner)->first();
    }

    public function zonal()
    {
        return $this->belongsTo('App\Models\Location\Zonal', 'zonal_id', 'code');
    }
    public function tas()
    {
        return $this->belongsTo('App\Models\Location\Ta', 'tas_id', 'code');
    }
    public function electoral()
    {
        return $this->belongsTo('App\Models\Location\Electoral', 'electoral_id', 'code');
    }
    public function street()
    {
        return $this->belongsTo('App\Models\Location\Street', 'street_id', 'code');
    }
}
