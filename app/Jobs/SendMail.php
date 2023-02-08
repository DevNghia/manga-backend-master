<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use DispatchesJobs, InteractsWithQueue, Queueable, SerializesModels;

    public $queue;

    private $mailTos = [];

    private $cc = [];

    private $bcc = [];

    /**
     * @var Mailable
     */
    private $mail;

    private $local;

    public function __construct(array $mailTos, Mailable $mail, ?array $cc = [], ?array $bcc = [], string $local= 'vi')
    {
        $this->mailTos = $mailTos;
        $this->mail = $mail;
        $this->cc = $cc;
        $this->bcc = $bcc;
        $this->local = $local;
    }

    public function handle()
    {
        $pendingMail = Mail::to($this->mailTos)->locale($this->local);

        if (!empty($this->cc)) {
            $pendingMail = $pendingMail->cc($this->cc);
        }

        if (!empty($this->bcc)) {
            $pendingMail = $pendingMail->bcc($this->bcc);
        }

        $pendingMail->send($this->mail);
    }
}
