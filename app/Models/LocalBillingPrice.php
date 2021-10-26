<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalBillingPrice extends Model
{
    use HasFactory;

    protected $fillable = ['price_id', 'product_id', 'type', 'currency', 'unit_amount'];

    /**
     * Get the phone associated with the user.
     */
    public function plan()
    {
        return $this->belongsTo(LocalBillingPlan::class, 'id');
    }
}
