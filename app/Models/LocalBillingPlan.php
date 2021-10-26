<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalBillingPlan extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name', 'desc'];

    /**
     * Get the price associated with plan (product)
     */
    public function price()
    {
        return $this->hasOne(LocalBillingPrice::class, 'product_no', 'id');
    }
}
