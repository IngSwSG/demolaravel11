<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateDatabase extends Command
{
    protected $signature = 'db:create';
    protected $description = 'Create an SQLite database file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $file = database_path('database.sqlite');
        if (!file_exists($file)) {
            touch($file);
            $this->info("Database file created at: " . $file);
        } else {
            $this->info("Database file already exists at: " . $file);
        }
    }
}
