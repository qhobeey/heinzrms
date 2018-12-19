<?php

namespace App;

class PropertyType extends Model
{
    public function categories(){
        return $this->hasMany(PropertyCategory::class);
    }
}
