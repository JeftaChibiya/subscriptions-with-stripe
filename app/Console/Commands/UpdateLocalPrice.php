<?php

namespace App\Console\Commands;

use App\Models\LocalBillingPrice;
use Illuminate\Console\Command;

class UpdateLocalPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:update-local-price {price}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the local billing price';

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
        $price = $this->argument('price');

        LocalBillingPrice::where('product', $price['product'])
                        ->update([
                            'type' => $price['type'],
                            'currency' => $price['currency'],
                            'unit_amount' => $price['unit_amount']
                        ]);
        // End + message
        $this->info('Succeeded');
    }
}
