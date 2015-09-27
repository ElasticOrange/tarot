<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emailbox extends Model
{
	protected $table = 'emailboxes';

	protected $fillable = [
		'name',
		'smtpServer',
		'smtpPort',
		'smtpEncryption',
		'smtpUsername',
		'smtpPassword',
		'imapServer',
		'imapPort',
		'imapProtocol',
		'imapEncryption',
		'imapFolder',
		'imapUsername',
		'imapPassword',
		'comment'
	];

	public function update(array $attributes = array()) {
        if (array_key_exists('smtpPassword', $attributes) and empty($attributes['smtpPassword'])) {
            unset($attributes['smtpPassword']);
        }

        if (array_key_exists('imapPassword', $attributes) and empty($attributes['imapPassword'])) {
            unset($attributes['imapPassword']);
        }

        return parent::update($attributes);
	}

	public function sites() {
		return $this->hasMany('App\Site', 'emailbox_id');
	}
}
