<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TStayFavorite extends Model
{
    protected $primaryKey = 'favorite_id';
    protected $fillable =['guest_id','stay_id'];    
}
