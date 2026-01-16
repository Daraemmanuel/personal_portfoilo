<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--path=}';
    protected $description = 'Backup the database';

    public function handle()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $filename = 'backup_' . date('Y-m-d_His') . '.sql';
        $path = $this->option('path') ?: storage_path('app/backups');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filepath = $path . '/' . $filename;

        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s > %s',
            escapeshellarg($host),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($filepath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Database backup created: {$filepath}");
        } else {
            $this->error('Failed to create database backup');
            return 1;
        }

        return 0;
    }
}
