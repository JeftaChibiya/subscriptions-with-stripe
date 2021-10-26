<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
      * Set User password
      *
      * @param  string  $value
      * @return void
      */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Subscribe the registered new to a Stripe billing plan
     *
     * @param [type] $paymentMethodId
     * @param [type] $plan_id
     * @return void
     */
    public function subscribeToBillingPlan($paymentMethodId, $plan_id)
    {
        $selectedPlan = LocalBillingPlan::where('plan_id', $plan_id)->first();
        $selectedPrice = $selectedPlan->price->price_id;
        $this->newSubscription('default', $selectedPrice)
             ->create($paymentMethodId, ['name' => $this->name, 'email' => $this->email]);
    }
}
