<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApplyOverdueFines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fees:apply-fines';

    protected $description = 'Automatically apply late fines to overdue invoices based on school rules.';

    public function handle(\App\Services\FeeService $feeService)
    {
        $this->info('Starting automated fine processing...');
        $feeService->applyOverdueFines();
        $this->info('Overdue fines applied successfully.');
    }
}
