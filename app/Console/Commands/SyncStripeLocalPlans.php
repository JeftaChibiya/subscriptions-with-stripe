<?php

namespace App\Console\Commands;

use App\Models\LocalBillingPlan;
use Stripe\StripeClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncStripeLocalPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:sync-plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update local billing plans';

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
        $stripe = new StripeClient(config('services.stripe.secret'));

        // create a Local billing plan that matches one in Stripe
        $stripeBillingPlans = $stripe->products->all(['active' => true]);

        DB::beginTransaction();

        DB::table('local_billing_plans')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('local_billing_plans')->truncate();
        DB::table('local_billing_prices')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::beginTransaction();

        // save each plan (product)
        foreach ($stripeBillingPlans as $stripeBillingPlan) {
            $savedPlan = LocalBillingPlan::create([
                'product_id' => $stripeBillingPlan->id,
                'name' => $stripeBillingPlan->name,
                'desc' => $stripeBillingPlan->description,
            ]);

            // source one price belonging to product
            $stripePrice = $stripe->prices->all([
                'product' => $stripeBillingPlan->id,
                'limit' => 1
            ]);

            // save price
            foreach ($stripePrice as $key => $value) {
                // save the relationship
                $savedPlan->price()->create([
                    'price_id' => $value['id'],
                    'product_id' => $value['product'],
                    'type' => $value['type'],
                    'currency' => $value['currency'],
                    'unit_amount' => $value['unit_amount']
                ]);
            }
        }

        DB::commit();

        // End + message
        $this->info('Succeeded');
    }
}
