<?php

namespace App\Jobs;

use App\Mail\ReportReadyMail;
use App\Models\Complaint;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $limit;

    protected $email;

    public function __construct($limit, $email)
    {
        $this->limit = $limit;
        $this->email = $email;
    }

    public function handle()
    {
        $complaints = $this->limit === 'all'
            ? Complaint::latest()->get()
            : Complaint::latest()->take((int) $this->limit)->get();

        $pdf = Pdf::loadView('admin.pdf_complaints', compact('complaints'));
        $filePath = 'reports/complaints_'.time().'.pdf';

        Storage::put('public/'.$filePath, $pdf->output());

        $downloadUrl = asset('storage/'.$filePath);

        Mail::to($this->email)->send(new ReportReadyMail($downloadUrl));
    }
}
