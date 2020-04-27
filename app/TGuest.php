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

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

// Please add this line

class TGuest extends Authenticatable implements JWTSubject
{

    use Notifiable;
    protected $primaryKey = 'guest_id';

    protected $table = 't_guest';

    

    protected $guard = 'guest';

    protected $fillable = [
        'name','phone', 'email', 'password','avatar'
    ];

    protected $hidden = [
        'password','token'
    ];

    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [];
    }
}
