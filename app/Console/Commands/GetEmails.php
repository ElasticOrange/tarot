<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;


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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mailbox = new ImapMailbox('{pop.mail.yahoo.com:995/service=pop3/ssl/notls/novalidate-cert}INBOX', 'test_tarot@yahoo.com', 't32tt4r0t', './public/attachments');
        $mails = array();

        $mailsIds = $mailbox->searchMailBox('ALL');
        $mail = $mailbox->getMail(3);
print_r($mail);

    }
}
