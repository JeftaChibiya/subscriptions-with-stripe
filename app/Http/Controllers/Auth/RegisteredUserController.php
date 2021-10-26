<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\LocalBillingPlan;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $clientSecret = $stripe->setupIntents->create([
            'payment_method_types' => ['card'],
        ]);

        $plans = LocalBillingPlan::all();

        return Inertia::render('Auth/Register', [
            'plans' => $plans,
            'clientSecret' => $clientSecret
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param RegisterUserRequest $request
     * @return void
     */
    public function store(RegisterUserRequest $request)
    {
        // ensure there are no '[]' brackets inside 'create'
        Auth::login($user = User::create($request->validated()));
        event(new Registered($user));
        $user->subscribeToBillingPlan($request->paymentMethodId, $request->plan_id);
        return redirect(RouteServiceProvider::HOME);
    }
}
