<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

function withError($message, $var = null) {
    $logMessage = $message.($var !== null ? print_r($var, true) : '');
    //echo $logMessage."\n";
    Log::error($logMessage);
}

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
		'text_content',
		'bounce'
	];

	protected $dates = ['created_at', 'updated_at', 'sent_at'];

	public function attachments() {
		return $this->hasMany('\App\Attachment','email_id', 'id');
	}

	public function client() {
		return $this->belongsTo('\App\Client', 'from_email', 'emailaddress');
	}

	public function fromSite() {
		return $this->belongsTo('\App\Site', 'from_email', 'owneremail');
	}

	public function toSite() {
		return $this->belongsTo('\App\Site', 'to_email', 'owneremail');
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
        return $query->where('from_email', $email)->orWhere('to_email', $email);
    }

    public function scopeNotBounced($query) {
    	return $query->where('bounce', 0);
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

	public function send() {

		$site = $this->fromSite()->first();
		if (!$site || !is_object($site)) {
			return withError('Email::send(): email sender does not correspond to any site: '.$this->from_email);
		}

		$mailbox = $site->getEmailbox();
		if (!$mailbox || !is_object($mailbox)) {
			return withError('Email::send(): site $site->name does not have mailbox set!');
		}

        $transport = Swift_SmtpTransport::newInstance($mailbox->smtpServer, $mailbox->smtpPort, $mailbox->smtpEncryption)
                                        ->setUsername($mailbox->smtpUsername)
                                        ->setPassword($mailbox->smtpPassword);

        $mailer = Swift_Mailer::newInstance($transport);

        // Create a message
        $message = Swift_Message::newInstance($this->subject)
                  ->setFrom(array($this->from_email => $this->from_name))
                  ->setTo(array($this->to_email => $this->to_name))
                  ->setBody($this->html_content, 'text/html');

        // Send the message
        $result = $mailer->send($message);

        if ($result) {
        	$this->sent_at = date('Y-m-d H:i:s');
        	$this->bounce = 0;
        	$this->save();
        }

        if (!$result) {
        	return withError('Email::send(): Email not send for site $site->name with emailbox $mailbox->name, email:', $this->attributes);
        }

        return $result;
	}

}
