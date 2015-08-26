<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
	protected $table = 'email_list_subscribers';
	use SoftDeletes;

	protected $primaryKey = 'subscriberid';


	public function getIdAttribute() {
		return $this->subscriberid;
	}

	public function setIdAttribute($value) {
		return $this->attributes['subscriberid'] = $value;
	}

	protected $dates = ['deleted_at'];

    public function data() {
        return $this->hasMany('\App\ClientData', 'subscriberid', 'subscriberid');
    }

    public function fields() {
        return $this->belongsToMany('App\ClientField', 'email_subscribers_data', 'subscriberid', 'fieldid')->withPivot('data');
    }

    public function site() {
        return $this->belongsTo('App\Site', 'listid', 'listid');
    }


    public function getProperties() {
    	$data = $this->data()->with('field')->get();

    	return $data;
    }
}
