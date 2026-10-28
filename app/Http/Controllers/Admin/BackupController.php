<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function index(): View
    {
        $backupDir = storage_path('app/backups');
        $files = [];

        if (File::exists($backupDir)) {
            foreach (File::files($backupDir) as $file) {
                if ($file->getExtension() === 'sql') {
                    $files[] = [
                        'name' => $file->getFilename(),
                        'size' => round($file->getSize() / 1024, 2) . ' KB',
                        'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }
        }

        // Sort by modified date descending
        usort($files, fn($a, $b) => strcmp($b['modified'], $a['modified']));

        return view('admin.backup.index', compact('files'));
    }

    public function create(): RedirectResponse
    {
        try {
            $pdo = DB::connection()->getPdo();
            $tables = [];
            $stmt = $pdo->query('SHOW TABLES');
            while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            $sqlScript = '-- Database Backup for ' . config('database.connections.mysql.database') . "\n";
            $sqlScript .= '-- Generated at: ' . now()->toDateTimeString() . "\n\n";

            foreach ($tables as $table) {
                $stmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
                $row = $stmt->fetch(\PDO::FETCH_NUM);
                $sqlScript .= "\n\n" . $row[1] . ";\n\n";

                $stmt = $pdo->query("SELECT * FROM `{$table}`");
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $keys = array_map(fn($k) => "`$k`", array_keys($row));
                    $values = array_map(fn($v) => is_null($v) ? 'NULL' : "'" . addslashes((string)$v) . "'", array_values($row));
                    $sqlScript .= "INSERT INTO `{$table}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
            }

            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            File::put($backupDir . '/' . $filename, $sqlScript);

            return redirect()->route('admin.backup.index')->with('success', "Backup file '{$filename}' created successfully.");
        } catch (\Throwable $e) {
            return redirect()->route('admin.backup.index')->withErrors(['backup' => 'Backup failed: ' . $e->getMessage()]);
        }
    }

    public function download(string $filename): BinaryFileResponse|RedirectResponse
    {
        $path = storage_path('app/backups/' . basename($filename));
        if (File::exists($path)) {
            return response()->download($path);
        }
        return redirect()->route('admin.backup.index')->withErrors(['backup' => 'File not found.']);
    }

    public function destroy(string $filename): RedirectResponse
    {
        $path = storage_path('app/backups/' . basename($filename));
        if (File::exists($path)) {
            File::delete($path);
            return redirect()->route('admin.backup.index')->with('success', 'Backup deleted.');
        }
        return redirect()->route('admin.backup.index')->withErrors(['backup' => 'File not found.']);
    }
}
