<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tripePayment extends Model
{
    protected $table="tripe_payment";
    protected $fillable = ['id_payment','amount','created','client_secret','customer','description','receipt_email','status','bookingId','userId','currency'];
}
