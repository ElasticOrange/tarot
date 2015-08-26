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
}

/*

	client -> valori -> field

 */
