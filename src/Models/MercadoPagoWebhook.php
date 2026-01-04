<?php

namespace Webkul\MercadoPago\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Sales\Models\Order;

class MercadoPagoWebhook extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mercadopago_webhooks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_id',
        'external_reference',
        'status',
        'status_detail',
        'payment_type',
        'payment_method_id',
        'transaction_amount',
        'transaction_amount_refunded',
        'installments',
        'description',
        'payer',
        'metadata',
        'additional_info',
        'response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payer' => 'array',
        'metadata' => 'array',
        'additional_info' => 'array',
        'response' => 'array',
        'transaction_amount' => 'float',
        'transaction_amount_refunded' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the order associated with the webhook.
     */
    public function order()
    {
        return $this->belongsTo(\Webkul\Sales\Models\Order::class, 'external_reference', 'id');
    }

    /**
     * Scope a query to only include webhooks for a specific payment ID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $paymentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPaymentId($query, $paymentId)
    {
        return $query->where('payment_id', $paymentId);
    }

    /**
     * Scope a query to only include webhooks for a specific external reference.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $externalReference
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByExternalReference($query, $externalReference)
    {
        return $query->where('external_reference', $externalReference);
    }

    /**
     * Scope a query to only include webhooks with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if the payment is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the payment is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the payment is rejected.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
