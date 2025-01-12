<?php

namespace Soufian212\LaraTransManager\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'translation:install';
    protected $description = 'Install the LaraTransManager package.';

    public function handle()
    {
        $this->info('Installing LaraTransManager...');

        // Publish Migrations
        $this->call('vendor:publish', [
            '--tag' => 'laratransmanager-migrations',
            '--force' => true,
        ]);

        // Publish Config
        $this->call('vendor:publish', [
            '--tag' => 'laratransmanager-config',
            '--force' => true,
        ]);

        // Publish Public Assets (Vendor Files)
        $this->call('vendor:publish', [
            '--tag' => 'public',
            '--force' => true,
        ]);

        // Ask to Run Migrations
        if ($this->confirm('Do you want to run the migrations now?')) {
            $this->call('migrate');
        }

        // Ask to Run Init Command
        if ($this->confirm('Do you want to run the translations:init command now?')) {
            $this->call('translations:init');
        }

        $this->info('LaraTransManager installation complete.');
    }
}