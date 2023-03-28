<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['status', 'payment_intent_id', 'customer_first_name', "customer_last_name", "customer_emil", "customer_phone", "shipping_address", "shipping_city", "shipping_state", 'shipping_postal_code', "billing_address", "quantity", "amount", "shipping", "created_at", "updated_at"];
}
