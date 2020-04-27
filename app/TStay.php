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

class TStay extends Model
{
    protected $table = 't_stay';
    
    protected $primaryKey = 'stay_id';

    protected $fillable = [
        'stay_name',
        'stay_type_id',
        'host_id',
        'address_id',
        'city_id',
        'host_id',
        'lang',
        'price',
        'discount',
        'description',
        'guest_count',
        'bed_count',
        'bath_count',
        'wifi',
        'smoking',
        'cooler',
        'refrigerator',
        'pool',
        'kitchen'
    ];

    public function address()
    {
        return $this->hasOne('App\TAddress','stay_id','stay_id');
    }
    public function type()
    {
        return $this->hasOne('App\MCode','id','stay_type_id');
    }
    public function city()
    {
        return $this->hasOne('App\MPlace','code_place','city_id');
    }
   

    public function room_gallery()
    {
        return $this->belongsTo('App\TRoomGallery','stay_id','stay_id');
    }

}
