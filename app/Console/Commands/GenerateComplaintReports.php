<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Complaint;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateComplaintReports extends Command
{
    protected $signature = 'report:generate {limit=10}'; // Example: php artisan report:generate 20
    protected $description = 'Generate pre-built complaint report PDF';

    public function handle()
    {
        $limit = $this->argument('limit');

        $complaints = ($limit === 'all')
            ? Complaint::with(['user', 'department'])->latest()->get()
            : Complaint::with(['user', 'department'])->latest()->take((int) $limit)->get();

        $pdf = Pdf::loadView('admin.pdf_complaints', ['complaints' => $complaints]);

        $filename = $limit === 'all' ? 'complaints_all.pdf' : "complaints_{$limit}.pdf";

        Storage::put("reports/{$filename}", $pdf->output());

        $this->info("Generated: storage/app/reports/{$filename}");
    }
}
