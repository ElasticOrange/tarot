<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientField extends Model
{
	protected $table = 'email_customfields';

	protected $primaryKey = 'fieldid';


	public function getIdAttribute() {
		return $this->fieldid;
	}

	public function setIdAttribute($value) {
		return $this->attributes['fieldid'] = $value;
	}

    public function values() {
        return $this->hasMany('\App\ClientData', 'fieldid', 'fieldid');
    }

    static public function getByName($name) {
    	$instance = new static;
    	return $instance->where('name', $name)->first();
    }
}

/*

	client -> valori -> field

 */
