<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
	protected $table = 'email_forms';

	protected $primaryKey = 'formid';

	public function sites() {
		return $this->belongsToMany('App\Site', 'email_form_lists', 'formid', 'listid');
	}

	public function fields() {
		return $this->belongsToMany('App\ClientField', 'email_form_customfields', 'formid', 'fieldid');
	}

	public function getIdAttribute() {
		return $this->formid;
	}
}
