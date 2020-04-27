<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_district extends Model
{
    protected $table = 'm_district';
    protected $primaryKey = 'district_id';

    public function ward()
    {
        return $this->hasMany('App\M_ward','ward_id','district_id');
    }
}
