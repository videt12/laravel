<?php

namespace App\Services\Backup;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DatabaseBackupService
{
    protected string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
        if (! File::exists($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
        }
    }

    /**
     * Generate a database backup in the requested format (sql, zip, json, csv).
     */
    public function createBackup(string $format = 'sql'): array
    {
        $format = strtolower(trim($format));

        return match ($format) {
            'json' => $this->createJsonBackup(),
            'zip' => $this->createZipSqlBackup(),
            'csv' => $this->createCsvZipBackup(),
            default => $this->createSqlBackup(),
        };
    }

    /**
     * Generate a complete SQL backup of the current database.
     */
    public function createSqlBackup(): array
    {
        $conn = DB::connection();
        $dbName = $conn->getDatabaseName();
        $pdo = $conn->getPdo();
        $timestamp = date('Y_m_d_His');
        $fileName = "bps_backup_{$dbName}_{$timestamp}.sql";
        $filePath = $this->backupDir.DIRECTORY_SEPARATOR.$fileName;

        $handle = fopen($filePath, 'w');
        if (! $handle) {
            throw new \Exception('មិនអាចបង្កើតឯកសារបម្រុងទុកក្នុង storage/app/backups បានឡើយ។');
        }

        // 1. SQL Header
        $now = date('Y-m-d H:i:s');
        fwrite($handle, "-- ============================================================\n");
        fwrite($handle, "-- Modern School Management System - Full Database Backup\n");
        fwrite($handle, "-- Generated At: {$now}\n");
        fwrite($handle, "-- Database: `{$dbName}`\n");
        fwrite($handle, "-- ============================================================\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
        fwrite($handle, "SET NAMES utf8mb4;\n\n");

        // 2. Fetch all base tables
        $tablesQuery = $conn->select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
        $tableKey = 'Tables_in_'.$dbName;

        foreach ($tablesQuery as $tableRow) {
            $rowArray = (array) $tableRow;
            $tableName = $rowArray[$tableKey] ?? reset($rowArray);

            if (empty($tableName)) {
                continue;
            }

            // Structure
            fwrite($handle, "-- ------------------------------------------------------------\n");
            fwrite($handle, "-- Table structure for `{$tableName}`\n");
            fwrite($handle, "-- ------------------------------------------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

            $createTableStmt = $conn->select("SHOW CREATE TABLE `{$tableName}`");
            if (! empty($createTableStmt)) {
                $createArray = (array) $createTableStmt[0];
                $createSql = $createArray['Create Table'] ?? '';
                fwrite($handle, $createSql.";\n\n");
            }

            // Data
            fwrite($handle, "-- Dumping data for table `{$tableName}`\n");
            $rows = $conn->table($tableName)->get();

            if ($rows->isNotEmpty()) {
                $chunks = $rows->chunk(100);
                foreach ($chunks as $chunk) {
                    $insertValues = [];
                    foreach ($chunk as $row) {
                        $values = [];
                        foreach ((array) $row as $val) {
                            if (is_null($val)) {
                                $values[] = 'NULL';
                            } elseif (is_numeric($val) && ! is_string($val)) {
                                $values[] = $val;
                            } else {
                                $values[] = $pdo->quote((string) $val);
                            }
                        }
                        $insertValues[] = '('.implode(', ', $values).')';
                    }

                    if (! empty($insertValues)) {
                        fwrite($handle, "INSERT INTO `{$tableName}` VALUES\n".implode(",\n", $insertValues).";\n");
                    }
                }
            }

            fwrite($handle, "\n");
        }

        // 3. SQL Footer
        fwrite($handle, "-- ============================================================\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fwrite($handle, "-- Backup Complete\n");
        fwrite($handle, "-- ============================================================\n");

        fclose($handle);

        $fileSize = filesize($filePath);

        return [
            'success' => true,
            'format' => 'sql',
            'filename' => $fileName,
            'filepath' => $filePath,
            'size' => $fileSize,
            'size_formatted' => $this->formatBytes($fileSize),
            'created_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * Generate a ZIP compressed SQL backup.
     */
    public function createZipSqlBackup(): array
    {
        // First create raw SQL
        $sqlResult = $this->createSqlBackup();
        $sqlFilePath = $sqlResult['filepath'];
        $sqlFileName = $sqlResult['filename'];

        $conn = DB::connection();
        $dbName = $conn->getDatabaseName();
        $timestamp = date('Y_m_d_His');
        $zipFileName = "bps_backup_{$dbName}_{$timestamp}.zip";
        $zipFilePath = $this->backupDir.DIRECTORY_SEPARATOR.$zipFileName;

        if (class_exists(\ZipArchive::class)) {
            $zip = new \ZipArchive;
            if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                $zip->addFile($sqlFilePath, $sqlFileName);
                $zip->close();

                // Remove uncompressed SQL file so only ZIP is kept
                if (file_exists($sqlFilePath)) {
                    unlink($sqlFilePath);
                }

                $zipSize = filesize($zipFilePath);

                return [
                    'success' => true,
                    'format' => 'zip',
                    'filename' => $zipFileName,
                    'filepath' => $zipFilePath,
                    'size' => $zipSize,
                    'size_formatted' => $this->formatBytes($zipSize),
                    'created_at' => Carbon::now()->toDateTimeString(),
                ];
            }
        }

        // Fallback to uncompressed SQL if zip fails
        return $sqlResult;
    }

    /**
     * Generate a JSON structured database backup.
     */
    public function createJsonBackup(): array
    {
        $conn = DB::connection();
        $dbName = $conn->getDatabaseName();
        $timestamp = date('Y_m_d_His');
        $fileName = "bps_backup_{$dbName}_{$timestamp}.json";
        $filePath = $this->backupDir.DIRECTORY_SEPARATOR.$fileName;

        $handle = fopen($filePath, 'w');
        if (! $handle) {
            throw new \Exception('មិនអាចបង្កើតឯកសារបម្រុងទុក JSON បានឡើយ។');
        }

        $tablesQuery = $conn->select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
        $tableKey = 'Tables_in_'.$dbName;

        fwrite($handle, "{\n");
        fwrite($handle, '  "metadata": '.json_encode([
            'database' => $dbName,
            'driver' => $conn->getDriverName(),
            'server_version' => $conn->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION),
            'generated_at' => date('Y-m-d H:i:s'),
            'total_tables' => count($tablesQuery),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).",\n");

        fwrite($handle, "  \"tables\": {\n");

        $isFirstTable = true;
        foreach ($tablesQuery as $tableRow) {
            $rowArray = (array) $tableRow;
            $tableName = $rowArray[$tableKey] ?? reset($rowArray);

            if (empty($tableName)) {
                continue;
            }

            if (! $isFirstTable) {
                fwrite($handle, ",\n");
            }
            $isFirstTable = false;

            fwrite($handle, '    '.json_encode($tableName).": [\n");

            $isFirstRow = true;
            foreach ($conn->table($tableName)->cursor() as $row) {
                if (! $isFirstRow) {
                    fwrite($handle, ",\n");
                }
                $isFirstRow = false;
                fwrite($handle, '      '.json_encode($row, JSON_UNESCAPED_UNICODE));
            }

            fwrite($handle, "\n    ]");
        }

        fwrite($handle, "\n  }\n}\n");
        fclose($handle);

        $fileSize = filesize($filePath);

        return [
            'success' => true,
            'format' => 'json',
            'filename' => $fileName,
            'filepath' => $filePath,
            'size' => $fileSize,
            'size_formatted' => $this->formatBytes($fileSize),
            'created_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * Generate a ZIP archive containing individual CSV files for all database tables.
     */
    public function createCsvZipBackup(): array
    {
        $conn = DB::connection();
        $dbName = $conn->getDatabaseName();
        $timestamp = date('Y_m_d_His');
        $zipFileName = "bps_backup_{$dbName}_csv_{$timestamp}.zip";
        $zipFilePath = $this->backupDir.DIRECTORY_SEPARATOR.$zipFileName;

        if (! class_exists(\ZipArchive::class)) {
            throw new \Exception('PHP ZipArchive extension មិនដំណើរការទេ។');
        }

        $zip = new \ZipArchive;
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('មិនអាចបង្កើតឯកសារ ZIP សម្រាប់ CSV បានឡើយ។');
        }

        $tablesQuery = $conn->select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
        $tableKey = 'Tables_in_'.$dbName;

        foreach ($tablesQuery as $tableRow) {
            $rowArray = (array) $tableRow;
            $tableName = $rowArray[$tableKey] ?? reset($rowArray);

            if (empty($tableName)) {
                continue;
            }

            $csvHandle = fopen('php://temp', 'r+');
            $hasHeader = false;

            foreach ($conn->table($tableName)->cursor() as $row) {
                $item = (array) $row;
                if (! $hasHeader) {
                    fputcsv($csvHandle, array_keys($item), ',', '"', '\\');
                    $hasHeader = true;
                }
                fputcsv($csvHandle, $item, ',', '"', '\\');
            }

            if (! $hasHeader) {
                $columns = Schema::getColumnListing($tableName);
                fputcsv($csvHandle, $columns, ',', '"', '\\');
            }

            rewind($csvHandle);
            $csvContent = stream_get_contents($csvHandle);
            fclose($csvHandle);

            $zip->addFromString("{$tableName}.csv", $csvContent);
        }

        $zip->close();
        $fileSize = filesize($zipFilePath);

        return [
            'success' => true,
            'format' => 'csv',
            'filename' => $zipFileName,
            'filepath' => $zipFilePath,
            'size' => $fileSize,
            'size_formatted' => $this->formatBytes($fileSize),
            'created_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * List all available backup files.
     */
    public function listBackups(): array
    {
        $files = File::files($this->backupDir);
        $backups = [];

        foreach ($files as $file) {
            $ext = strtolower($file->getExtension());
            if (in_array($ext, ['sql', 'zip', 'json', 'csv', 'gz'])) {
                $formatType = $ext;
                if ($ext === 'zip') {
                    $formatType = str_contains($file->getFilename(), '_csv_') ? 'csv' : 'zip';
                }

                $backups[] = [
                    'filename' => $file->getFilename(),
                    'extension' => $ext,
                    'format_type' => $formatType,
                    'path' => $file->getRealPath(),
                    'size' => $file->getSize(),
                    'size_formatted' => $this->formatBytes($file->getSize()),
                    'created_at' => Carbon::createFromTimestamp($file->getMTime()),
                    'created_at_human' => Carbon::createFromTimestamp($file->getMTime())->diffForHumans(),
                ];
            }
        }

        // Sort latest backups first
        usort($backups, function ($a, $b) {
            return $b['created_at']->timestamp <=> $a['created_at']->timestamp;
        });

        return $backups;
    }

    /**
     * Delete a specific backup file.
     */
    public function deleteBackup(string $fileName): bool
    {
        $safeName = basename($fileName);
        $path = $this->backupDir.DIRECTORY_SEPARATOR.$safeName;

        if (File::exists($path)) {
            return File::delete($path);
        }

        return false;
    }

    /**
     * Get real-time database telemetry and stats.
     */
    public function getDatabaseStats(): array
    {
        $conn = DB::connection();
        $dbName = $conn->getDatabaseName();

        try {
            $tableStats = DB::select('
                SELECT 
                    COUNT(*) as total_tables,
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables 
                WHERE table_schema = ?
            ', [$dbName]);

            $totalTables = $tableStats[0]->total_tables ?? 0;
            $sizeMb = $tableStats[0]->size_mb ?? 0.0;
        } catch (\Throwable $e) {
            $totalTables = count($conn->select('SHOW TABLES'));
            $sizeMb = 0.0;
        }

        $pdo = $conn->getPdo();
        $version = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
        $driver = $conn->getDriverName();

        return [
            'database_name' => $dbName,
            'driver' => strtoupper($driver),
            'server_version' => $version,
            'total_tables' => $totalTables,
            'size_mb' => $sizeMb,
        ];
    }

    /**
     * Helper to format bytes to KB/MB.
     */
    public function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }
}
