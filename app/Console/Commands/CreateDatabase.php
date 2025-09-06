<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    protected $signature = 'db:create {name?}';
    protected $description = 'Create the configured MySQL database if it does not exist';

    public function handle(): int
    {
        // Prefer config (works even when config is cached), then fallback to env
        $dbName = $this->argument('name')
            ?? config('database.connections.mysql.database')
            ?? env('DB_DATABASE');

        if (blank($dbName)) {
            $this->error('No database name available. Check config(database.connections.mysql.database) or DB_DATABASE.');
            return self::FAILURE;
        }

        try {
            $charset   = config('database.connections.mysql.charset', 'utf8mb4');
            $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

            // IMPORTANT: make sure you added the 'mysql_server' connection in config/database.php with database => null
            DB::connection('mysql_server')->statement(
                "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE {$collation}"
            );

            $this->info("Database `{$dbName}` is ready.");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to create database: '.$e->getMessage());
            return self::FAILURE;
        }
    }
}
