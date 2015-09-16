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

	public function client() {
		return $this->belongsTo('\App\Client', 'from_email', 'emailaddress');
	}

	public function scopeToEmail($query, $email) {
		return $query->where('to_email', $email);
	}

	public function scopeReceived($query) {
		return $query->where('sent', 0);
	}

	public function scopeUnresponded($query) {
		return $query->where('responded', 0);
	}

    public function scopeForEmailAddress($query, $email) {
        return $query->where('from_email', $email)->orWhere('to_email', $email)->orderBy('sent_at', 'asc');
    }

    public function scopeUnrespondedEmailsForSite($query, $site) {
    	return $query->received()
					->unresponded()
					->toEmail($site->email)
					->groupBy('from_email')
					->with(['client' => function($query) use ($site) {
			            $query->where('listid', $site->id);
					}]);
    }

	static public function getUnrespondedEmailsForSite($site) {
        $instance = new static;
        $emails = $instance	->unrespondedEmailsForSite($site)
        					->select(\DB::raw('*, count(id) as email_count'))
        					->orderBy('sent_at', 'desc')
        					->get();
        return $emails;
	}

}
