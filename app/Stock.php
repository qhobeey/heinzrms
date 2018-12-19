<?php

namespace App;
use App\Accountant;
use App\Supervisor;
use App\Collector;

class Stock extends Model
{
    public function get_name_person()
    {
        $id = '';
        if(!empty($this->accountant_id)):
            $accountant = Accountant::find($this->accountant_id)->first();
            $id = $accountant->name;
        endif;
        if(!empty($this->collector_id)):
            $collector = Collector::find($this->collector_id)->first();
            $id = $collector->name;
        endif;
        if(!empty($this->supervisor_id)):
            $supervisor = Supervisor::find($this->supervisor_id)->first();
            $id = $supervisor->name;
        endif;

        return $id;
    }
    public function gcr()
    {
        return $this->hasMany(EnumGcr::class);
    }
}