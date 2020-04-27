<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_province extends Model
{
    protected $table ="m_province";
    protected $primaryKey = 'province_id';

    public function district()
    {
        return $this->hasMany('App\M_district','district_id','province_id');
    }
}
