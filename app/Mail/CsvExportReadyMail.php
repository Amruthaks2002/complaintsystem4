<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CsvExportReadyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $downloadUrl;

    public function __construct($downloadUrl)
    {
        $this->downloadUrl = $downloadUrl;
    }

    public function build()
    {
        return $this->subject('Your Complaint CSV Report is Ready')
            ->markdown('emails.csv-export-ready')
            ->with([
                'downloadUrl' => $this->downloadUrl
            ]);
    }
}
