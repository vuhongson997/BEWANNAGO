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

class TStayRating extends Model
{
    protected $primaryKey = 'rating_id';

    protected $table = 't_stay_rating';
    
    protected $fillable = ['comment','stay_id','rate','guest_id'];
}
