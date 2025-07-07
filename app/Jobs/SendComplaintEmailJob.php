<?php

namespace App\Jobs;

use App\Mail\ComplaintSubmittedMail;
use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendComplaintEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $complaintId;

    public function __construct(Complaint $complaint)
    {
        $this->complaintId = $complaint->id;
    }

    public function handle()
    {
        $complaint = Complaint::with('user', 'department')->find($this->complaintId);

        if (! $complaint) {
            return;
        }

        $adminEmail = 'admin@example.com';
        Mail::to($adminEmail)->send(new ComplaintSubmittedMail($complaint));
    }
}
