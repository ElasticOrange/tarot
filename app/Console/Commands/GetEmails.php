<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use App\Email as Email;

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

    private function saveMailToDb($mail) {
        if (!$mail) {
            return false;
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
        $email->save();

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

        $mailbox = new ImapMailbox('{imap.mail.yahoo.com:993/service=imap/ssl/notls/novalidate-cert}INBOX', 'test_tarot@yahoo.com', 't32tt4r0t', './public/attachments');

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

            if ($this->saveMailToDb($mail)) {
                $mailbox->markMailAsRead($mailId);
            }
        }

        echo "\nDone\n";
    }
}
