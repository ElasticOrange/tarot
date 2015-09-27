<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use App\Email as Email;
use App\Attachment as Attachment;


function withError($message, $var) {
    echo $message.'\n';

    if ($var) {
        print_r($var);
    }

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
        if (!$mail) {
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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //$mailbox = new ImapMailbox('{pop.mail.yahoo.com:995/service=pop3/ssl/notls/novalidate-cert}INBOX', 'test_tarot@yahoo.com', 't32tt4r0t', './public/attachments');

        $mailbox = new ImapMailbox('{imap.mail.yahoo.com:993/service=imap/ssl/notls/novalidate-cert}INBOX', 'test_tarot@yahoo.com', 't35t_t4rot', './public/attachments');

        $mails = array();

        $mailIds = $mailbox->searchMailBox('SEEN'); //UNSEEN

        if (!count($mailIds)) {
            echo("No emails found! \n");
            return false;
        }

        foreach ($mailIds as $key => $mailId) {
            $mail = $mailbox->getMail($mailId);
            $errs = imap_errors(); //prevent errors from parsing emails

            if (count($errs)) {
                print_r($errs);
            }

            if (!$mail) {
                echo "Could not read email with id $mailId \n";
                continue;
            }

            if ($this->emailExists($mail)) {
                echo('Mail exists '.$mail->fromAddress.': '.$mail->subject."\n");
                continue;
            }

            echo('Saving mail '.$mail->fromAddress.': '.$mail->subject.' '.$mail->date."\n");

            if ($this->saveMailToDb($mail)) {
                $mailbox->markMailAsRead($mailId);
            }
        }

        echo "\nDone\n";
    }
}
