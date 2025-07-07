<?php

namespace App\Jobs;

use App\Mail\CsvExportReadyMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GenerateCsvAndSendLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $limit;
    public $adminEmail;

    public function __construct($adminEmail, $limit)
    {
        $this->limit = $limit;
        $this->adminEmail = $adminEmail;
    }

    public function handle()
    {
        try {
            // Allow unlimited time/memory for large exports
            ini_set('memory_limit', '1G');
            ini_set('max_execution_time', 0);

            $fileName = 'complaints_' . now()->format('Ymd_His') . '.csv';
            $filePath = 'exports/' . $fileName;

            // Create directory if not exists
            Storage::disk('public')->makeDirectory('exports');

            $fullPath = storage_path("app/public/{$filePath}");
            $handle = fopen($fullPath, 'w');

            // CSV headers
            fputcsv($handle, ['Title', 'Description', 'Status', 'Created At', 'Department']);

            // Prepare query
            $query = DB::table('complaints')
                ->leftJoin('departments', 'complaints.department_id', '=', 'departments.id')
                ->select(
                    'complaints.title',
                    'complaints.description',
                    'complaints.status',
                    'complaints.created_at',
                    'departments.name as department_name'
                )
                ->orderByDesc('complaints.id');

            if ($this->limit !== 'all') {
                $query->limit((int) $this->limit);
            }

            // Stream rows using cursor (best for performance)
            foreach ($query->cursor() as $row) {
                fputcsv($handle, [
                    $row->title,
                    $row->description,
                    $row->status,
                    $row->created_at,
                    $row->department_name ?? 'N/A',
                ]);
            }

            fclose($handle);

            // Send email with download link
            $downloadUrl = asset("storage/{$filePath}");
            Mail::to($this->adminEmail)->queue(new CsvExportReadyMail($downloadUrl));

        } catch (\Throwable $e) {
            Log::error('CSV Export failed: ' . $e->getMessage());
        }
    }
}
