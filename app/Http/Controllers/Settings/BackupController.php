<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\Backup\DatabaseBackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    /**
     * Trigger Database Backup in selected format.
     */
    public function store(Request $request, DatabaseBackupService $backupService)
    {
        try {
            $format = $request->input('format', 'sql');
            $result = $backupService->createBackup($format);

            $formatLabel = strtoupper($result['format'] ?? $format);

            return redirect()->route('settings.index', ['tab' => 'backup-logs'])
                ->with('success', "ការបម្រុងទុកមូលទិន្នន័យ (Database Backup) ជាទម្រង់ [{$formatLabel}] ត្រូវបានបង្កើតដោយជោគជ័យ! ទំហំ៖ {$result['size_formatted']}");
        } catch (\Throwable $e) {
            return redirect()->route('settings.index', ['tab' => 'backup-logs'])
                ->with('error', 'បរាជ័យក្នុងការបង្កើតការបម្រុងទុក៖ '.$e->getMessage());
        }
    }

    /**
     * Download an existing backup file.
     */
    public function download(string $filename, DatabaseBackupService $backupService)
    {
        $safeName = basename($filename);
        $path = storage_path('app/backups/'.$safeName);

        if (! File::exists($path)) {
            return redirect()->route('settings.index', ['tab' => 'backup-logs'])
                ->with('error', 'រកមិនឃើញឯកសារបម្រុងទុកនេះឡើយ។');
        }

        $mimeTypes = [
            'sql' => 'application/sql',
            'json' => 'application/json',
            'zip' => 'application/zip',
            'csv' => 'text/csv',
            'gz' => 'application/gzip',
        ];
        $ext = strtolower(pathinfo($safeName, PATHINFO_EXTENSION));
        $contentType = $mimeTypes[$ext] ?? 'application/octet-stream';

        return response()->download($path, $safeName, [
            'Content-Type' => $contentType,
        ]);
    }

    /**
     * Delete an existing backup file.
     */
    public function destroy(string $filename, DatabaseBackupService $backupService)
    {
        $safeName = basename($filename);
        $deleted = $backupService->deleteBackup($safeName);

        if ($deleted) {
            return redirect()->route('settings.index', ['tab' => 'backup-logs'])
                ->with('success', "ឯកសារ [{$safeName}] ត្រូវបានលុបដោយជោគជ័យ។");
        }

        return redirect()->route('settings.index', ['tab' => 'backup-logs'])
            ->with('error', "បរាជ័យក្នុងការលុបឯកសារ [{$safeName}]។");
    }
}
