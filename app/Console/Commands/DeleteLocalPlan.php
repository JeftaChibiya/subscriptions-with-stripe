<?php

namespace App\Console\Commands;

use App\Models\LocalBillingPlan;
use Illuminate\Console\Command;

class DeleteLocalPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:delete-local-plan {plan_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete local billing plan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        LocalBillingPlan::where('plan_id', $this->argument('plan_id'))->delete();
        // refresh increments and start at 0
        $this->call('stripe:sync-plans');
        // End + message
        $this->info('Succeeded');
    }
}
