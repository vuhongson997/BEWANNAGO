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

class TOccupiedReservation extends Model
{
    protected $primaryKey = 'occupied_reservation_id';

    protected $table = 't_occupied_reservations';

    protected $fillable =[
      'check_out',
      'check_in',
      'status',
      'stay_id',
      'guest_id',
      'cus_name',
      'phone',
      'email',
      'guest_count',
      'price',
      'reservation_id',
      'payment_id',
      'secret_id'
    ];
    public function stay()
    {
        return $this->belongsTo('App\TStay','stay_id','stay_id');
    }
}
