<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientData extends Model
{

    protected $primaryKey = null;
    public $incrementing = false;

	protected $table = 'email_subscribers_data';


    public function client() {
        return $this->belongsTo('\App\Client', 'subscriberid', 'subscriberid');
    }

    public function field() {
        return $this->belongsTo('\App\ClientField', 'fieldid', 'fieldid');
    }

}
