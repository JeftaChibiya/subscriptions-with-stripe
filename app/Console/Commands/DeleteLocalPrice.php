<?php

namespace App\Console\Commands;

use App\Models\LocalBillingPrice;
use Illuminate\Console\Command;

class DeleteLocalPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:delete-local-price {product}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        LocalBillingPrice::where('product', $this->argument('product'))->delete();
        // refresh increments and start at 0
        $this->call('stripe:sync-plans');
        // End + message
        $this->info('Succeeded');
    }
}
