<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComplaintReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfPath;

    public function __construct($pdfPath)
    {
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Complaint Report - ' . date('Y-m-d'))
                   ->markdown('emails.complaint_report')
                   ->attach($this->pdfPath, [
                       'as' => 'complaint_report.pdf',
                       'mime' => 'application/pdf',
                   ]);
    }
}