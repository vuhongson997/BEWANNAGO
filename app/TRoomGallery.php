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

class TRoomGallery extends Model
{
    protected $primaryKey = 'gallery_id';

    protected $table = 't_room_gallery';

    protected $fillable = [
      'stay_id',
      'img_url'
  ];
  public function stay()   
  {
      return $this->belongsTo('App\TStay', 'stay_id');
  }
}
