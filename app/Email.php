<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
	protected $fillable = [
		'sent',
		'from_email',
		'from_name',
		'to_email',
		'to_name',
		'subject',
		'sent_at',
		'html_content',
		'text_content'
	];

	protected $dates = ['created_at', 'updated_at', 'sent_at'];

	public function attachments() {
		return $this->hasMany('\App\Attachment','email_id', 'id');
	}

	static public function forEmailAddress($email) {
		$instance = new static;

		$result = $instance->where('from_email', $email)->orWhere('to_email', $email)->orderBy('sent_at', 'asc');

		return $result;
	}

	static public function scopeReceived($query) {
		return $query->where('sent', 0);
	}

	static public function scopeUnresponded($query) {
		return $query->where('responded', 0);
	}

	static public function getUnrespondedEmails() {
        $instance = new static;

        $emails = $instance->select(\DB::raw('*, count(id) as email_count'))->received()->unresponded()->groupBy('from_email')->orderBy('sent_at', 'desc')->get();

        return $emails;
	}
}
