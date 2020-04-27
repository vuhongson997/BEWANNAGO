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

class TReservationDetail extends Model
{
    protected $primaryKey = 'reservation_detail_id';

    protected $table = 't_reservation_detail';

    protected $fillable =[
      'check_out',
      'check_in',
      'quantity',
      'status',
      'reservation_id',
      'stay_id'
    ];

    public function stay()
    {
        return $this->belongsTo('App\TStay','stay_id','stay_id');
    }
}
