<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\LocalBillingPlan;
// use App\Models\User;
use Laravel\Cashier\Invoice;
// use App\Notifications\UserSubscribed;
use Illuminate\Support\Facades\Artisan;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Stripe\Invoice as StripeInvoice;
use Stripe\Stripe;

class WebhookController extends CashierController
{
    /**
     * customer.subscription.created
     *
     * @return void
     */
    public function handleCustomerSubscriptionCreated($payload)
    {
        // $user = User::where('stripe_id', $payload['data']['object']['customer']);
        // $receipent = $payload['data']['object']['customer'];
        $invoiceId = $payload['data']['object']['latest_invoice'];
        // $yearMonth = Carbon::now('Europe/London')->format('Y M');
        // $invoice = $this->getInvoice(
        //     $user,
        //     $receipent,
        //     $invoiceId,
        //     $payload,
        //     $yearMonth
        // );

        // $user->notify(new UserSubscribed($invoice, $invoiceId, $yearMonth));

        return 'Success.';
    }

    /**
     * New product created on Stripe
     *
     * @return void
     */
    public function handleProductCreated()
    {
        Artisan::call('stripe:sync-plans');
    }

    /**
     * Also update the local plan (product)
     *
     * @return void
     */
    public function handleProductUpdated($payload)
    {
        Artisan::call('stripe:update-local-plan', [
            'plan' => $payload['data']['object']
        ]);
    }

    /**
     * Also delete local plan (product)
     *
     * @return void
     */
    public function handleProductDeleted($payload)
    {
        Artisan::call('stripe:delete-local-plan', [
            'plan_id' => $payload['data']['object']['id']
        ]);
    }

    /**
     * Also update the local price
     *
     * @return void
     */
    public function handlePriceUpdated($payload)
    {
        Artisan::call('stripe:update-local-price', [
            'price' => $payload['data']['object']
        ]);
    }

    /**
     * Also delete local price
     *
     * @return void
     */
    public function handlePriceDeleted($payload)
    {
        Artisan::call('stripe:delete-local-price', [
            'product' => $payload['data']['object']['product']
        ]);
    }

    /**
     * Get invoice from Stripe
     *
     * @param [type] $user
     * @param [type] $receipent
     * @param [type] $invoiceId
     * @param [type] $payload
     * @return void
     */
    public function getInvoice($user, $receipent, $invoiceId, $payload, $yearMonth)
    {
        // Not include keys" items,data, straight to key: plan
        $product = $payload['data']['object']['plan']['product'];
        // first instead of get(). get() returns collection that needs iteration
        $plan = LocalBillingPlan::where('plan_id', $product)->first();
        // ..
        // Stripe::setApiKey(config('services.stripe.secret'));
        // $invoiceReq = StripeInvoice::retrieve($invoiceId);
        $invoiceReq = $user->findInvoice($invoiceId);
        $lines = StripeInvoice::allLines($invoiceId);
        $invoiceObj = new Invoice($receipent, $invoiceReq);
        $invoice = $invoiceObj->pdf([$lines]);

        return $invoice;
    }
}
