<?php
/* 
 * Author: Khoa Trần
 * ADD: 
	- 
 * EDIT:
    - Tên File và Class
    -
 * DELETE
    -   
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class TAddress extends Model
{
    protected $primaryKey = 'address_id';
    protected $table = 't_address';

    // public function stays()
    // {
    //     return $this->hasMany('App\T_stay','stay_id','address_id');
    // }
    public function ward()
    {
        return $this->hasOne('App\M_ward','ward_id','ward_id');
    }
    public function district_name()
    {
        return $this->hasOne('App\M_district','district_id','district_id');
    }

    public function province()
    {
        return $this->hasOne('App\M_province','province_id','city_id');
    }


    protected $fillable = [
        'stay_id',
        'city_id',
        'district_id',
        'ward_id',
        'area'
    ];

   
}
