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
}
