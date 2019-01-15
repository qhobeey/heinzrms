<?php

namespace App;

class PropertyType extends Model
{
	protected $table = 'property_types';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    public function categories(){
        return $this->hasMany(PropertyCategory::class, 'type_id');
    }
}
