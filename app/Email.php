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

	static public function forEmailAddress($email) {
		$instance = new static;

		$result = $instance->where('from_email', $email)->orWhere('to_email', $email)->orderBy('sent_at', 'asc');

		return $result;
	}
}
