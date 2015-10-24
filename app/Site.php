<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
	protected $table = 'email_lists';
	use SoftDeletes;

	protected $primaryKey = 'listid';

	protected $dates = ['deleted_at'];

	protected $fillable = [
		'name',
		'url',
		'email',
		'sender',
		'subject',
		'country',
		'unsubscribe',
		'signature',
		'active',
		'emailbox_id'
	];

	public function getIdAttribute() {
		return $this->listid;
	}

	public function setIdAttribute($value) {
		return $this->attributes['listid'] = $value;
	}

	public function getEmailAttribute() {
		return $this->owneremail;
	}

	public function setEmailAttribute($value) {
		return $this->attributes['owneremail'] = $value;
	}

	public function getSenderAttribute() {
		return $this->ownername;
	}

	public function setSenderAttribute($value) {
		return $this->attributes['ownername'] = $value;
	}

	public function infocosts() {
		return $this->hasMany('App\Infocost');
	}

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function users() {
        return $this->belongsToMany('\App\User')->withTimestamps();
    }

    public function templates() {
        return $this->hasMany('\App\Template');
    }

    public function clients() {
        return $this->hasMany('\App\Client', 'listid', 'listid');
    }

    public function forms() {
		return $this->belongsToMany('App\Form', 'email_form_lists', 'listid', 'formid');
	}

	public function emailbox() {
		return $this->belongsTo('App\Emailbox', 'emailbox_id');
	}

	public function getForm() {
		return $this->forms()->first();
	}

    public function emails() {
        return $this->hasMany('\App\Email', 'to_email', 'owneremail');
    }

	// returns fields for the first form of the site (it may have more than one form)
	public function fields() {
		$form = $this->forms->first();

		if (!$form) {
			return false;
		}

		return $form->fields();
	}

	public function getClientByEmail($email) {
		return $this->clients()->where('emailaddress', $email)->first();
	}

	public function getEmailbox() {
		return $this->emailbox()->first();
	}

	public function hasUser($user) {
		return $this->users()->where('id', $user->id)->first();
	}
}
