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

class MCode extends Model
{
    protected $table = 'm_code';
    protected $fillable = ['code_name'];


}
