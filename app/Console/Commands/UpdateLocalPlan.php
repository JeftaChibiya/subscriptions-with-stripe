<?php

namespace App\Console\Commands;

use App\Models\LocalBillingPlan;
use Illuminate\Console\Command;

class UpdateLocalPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:update-local-plan {plan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a local billing plan';

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
        $product = $this->argument('plan');
        LocalBillingPlan::where('plan_id', $product['id'])
                        ->update([
                            'name' => $product['name'],
                            'desc' => $product['name'],
                            'name' => $product['description']
                        ]);
        // End + message
        $this->info('Succeeded');
    }
}
