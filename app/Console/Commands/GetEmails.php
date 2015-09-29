<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use App\Email as Email;
use App\Attachment as Attachment;
use Log;

function log($message, $var = null) {
    $logMessage = $message.($var !== null ? print_r($var, true) : '');

    echo $logMessage."\n";

    Log::info($logMessage);
}

function warn($message, $var = null) {
    $logMessage = $message.($var !== null ? print_r($var, true) : '');

    echo $logMessage."\n";

    Log::warning($logMessage);
}

function error($message, $var = null) {
    $logMessage = $message.($var !== null ? print_r($var, true) : '');

    echo $logMessage."\n";

    Log::error($logMessage);
}

function withError($message, $var) {
    error($message, $var);
    return false;
}


function getFileNameFromPath($path) {
    preg_match ( '/.*\/(.+)/' , $path, $matches);

    if (!empty($matches)) {
        return $matches[1];
    }

    return $path;
}

function getFolderPathFromPath($path) {
    preg_match ( '/(.*\/).+/' , $path, $matches);

    if (!empty($matches)) {
        return $matches[1];
    }

    return '';
}

class GetEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get tarot emails from email server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    private function saveAttachmentToDb($attachment, $mailId) {
        if (!is_object($attachment)) {
            return withError('saveAttachmentToDb: attachment is not object', $attachment);
        }

        if (!($mailId > 0)) {
            return withError('saveAttachmentToDb: mail id is not integer', $mailId);
        }

        $attach = new Attachment;

        $data = [
            'file' => getFileNameFromPath($attachment->filePath),
            'folder' => getFolderPathFromPath($attachment->filePath),
            'email_id' => $mailId
        ];

        $attach->fill($data);

        if (!$attach->save()) {
            return withError('saveAttachmentToDb: could not save attachment to DB');
        }

        return true;
    }

    private function saveMailToDb($mail) {
        if (!is_object($mail)) {
            return withError('saveMailToDb: mail is not object', $mail);
        }

        $email = new Email;

        $email->fill([
            'sent' => 0,
            'from_email' => $mail->fromAddress,
            'from_name' => $mail->fromName,
            'to_email' => (current($mail->to) ? current($mail->to) : ''),
            'to_name' => (key($mail->to) ? key($mail->to) : ''),
            'subject' => $mail->subject,
            'sent_at' => $mail->date,
            'html_content' => ($mail->textHtml ? $mail->textHtml : ''),
            'text_content' => ($mail->textPlain ? $mail->textPlain : '')
        ]);

        if (!$email->save()) {
            return withError("Could not save mail to db");
        }

        if (count($mail->getAttachments())) {
            foreach($mail->getAttachments() as $attachment) {
                $this->saveAttachmentToDb($attachment, $email->id);
            }
        }

        return true;
    }

    public function emailExists($mail) {

        if (!is_object($mail)) {
            return false;
        }

        $fromEmail = ($mail->fromAddress ? $mail->fromAddress : '');
        $toEmail = (current($mail->to) ? current($mail->to) : '');
        $sentAt = ($mail->date ? $mail->date : '');
        $subject = ($mail->subject ? $mail->subject : '');

        $email = Email  ::where('from_email', '=', $fromEmail)
                        ->where('to_email', '=', $toEmail)
                        ->where('sent_at', '=', $sentAt)
                        ->where('subject', '=', $subject)
                        ->get();


        if ($email->isEmpty()) {
            return false;
        }

        return true;
    }

    public function getMailboxReceiveString($mailbox) {
        $server = $mailbox->imapServer.':'.$mailbox->imapPort;

        $service = '';
        if ($mailbox->imapProtocol == 'imap') {
            $service = '/service=imap';
        }
        else if ($mailbox->imapProtocol == 'pop') {
            $service = '/service=pop3';
        }

        $encryption = '';
        if ($mailbox->imapEncryption == 'ssl') {
            $encryption = '/ssl/notls/novalidate-cert';
        }
        else if ($mailbox->imapEncryption == 'tls') {
            $encryption = '/ssl/tls/novalidate-cert';
        }

        $string = '{'.$server.$service.$encryption.'}'.$mailbox->imapFolder;

        return $string;
    }

    public function getEmailsFromMailbox($emailbox) {
        if (!$emailbox || !is_object($emailbox)) {
            return false;
        }

        $mailboxString = $this->getMailboxReceiveString($emailbox);

        log('Connecting to mailbox '.$mailboxString.' '.$emailbox->imapUsername);

        $mailbox = new ImapMailbox($mailboxString, $emailbox->imapUsername, $emailbox->imapPassword, './public/attachments');

        if (!$mailbox) {
            return withError('getEmailsFromMailbox: could not connect to mailbox', $emailbox);
        }

        $mailIds = $mailbox->searchMailBox('SEEN'); //UNSEEN

        if (!count($mailIds)) {
            log("No emails found");
            return false;
        }

        foreach ($mailIds as $key => $mailId) {
            $mail = $mailbox->getMail($mailId);
            $errs = imap_errors(); //prevent errors (exceptions) from parsing emails

            if (count($errs)) {
                print_r($errs);
            }

            if (!$mail) {
                warn("Could not read email with id $mailId");
                continue;
            }

            if ($this->emailExists($mail)) {
                warn('Mail exists '.$mail->fromAddress.': '.$mail->subject);
                continue;
            }

            log('Saving mail '.$mail->fromAddress.': '.$mail->subject.' '.$mail->date);

            if ($this->saveMailToDb($mail)) {
                $mailbox->markMailAsRead($mailId);
            }
        }

        log("Done");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sites = \App\Site::where('active', '1')->get();

        if (!$sites || $sites->isEmpty()) {
            return withError('Error: There are no active sites!');
        }

        foreach ($sites as $site) {
            $mailbox = $site->getEmailbox();

            log('Getting email for site: '.$site->name);

            $this->getEmailsFromMailbox($mailbox);
        }
    }
}
