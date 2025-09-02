<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'amount', 'taxa_cash_in', 'taxa_cash_out', 'expiration', 'active', 'custom'
    ];

    public function features()
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(PlanSubscription::class);
    }
}