<?php

namespace Webkul\MercadoPago\Payment;

use Webkul\Payment\Payment\Payment;
use MercadoPago\Client\PaymentClient;
use MercadoPago\Client\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Payment as MercadoPagoPayment;
use MercadoPago\Item as MercadoPagoItem;
use MercadoPago\Payer as MercadoPagoPayer;

class MercadoPago extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'mercadopago';

    /**
     * Mercado Pago SDK instance
     *
     * @var \MercadoPago\SDK
     */
    protected $mercadopago;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initializeMercadoPago();
    }

    /**
     * Initialize Mercado Pago SDK
     *
     * @return void
     */
    protected function initializeMercadoPago()
    {
        $accessToken = $this->getConfigData('access_token');

        if ($accessToken) {
            SDK::setAccessToken($accessToken);
            SDK::setIntegratorId('bagisto_mp');
            
            if ($this->getConfigData('debug')) {
                SDK::enableRetryRequests(true);
            }
        }
        
        $this->mercadopago = new SDK();
    }

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        if (! $this->cart) {
            $this->setCart();
        }

        $preference = $this->createPreference($this->cart);
        
        return $this->getConfigData('sandbox') 
            ? $preference->sandbox_init_point 
            : $preference->init_point;
    }

    /**
     * Create a preference in Mercado Pago
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return \MercadoPago\Preference
     */
    protected function createPreference($cart)
    {
        $preference = new Preference();
        
        // Configurar itens do carrinho
        $items = $this->getItems($cart);
        $preference->items = $items;
        
        // Configurar pagador
        $preference->payer = $this->getPayer($cart);
        
        // Configurar URLs de retorno
        $preference->back_urls = [
            'success' => route('mercadopago.success'),
            'failure' => route('mercadopago.failure'),
            'pending' => route('mercadopago.pending'),
        ];
        
        $preference->auto_return = 'approved';
        $preference->external_reference = $cart->id;
        $preference->notification_url = route('mercadopago.webhook');
        
        $preference->save();
        
        return $preference;
    }

    /**
     * Get items from cart
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return array
     */
    protected function getItems($cart)
    {
        $items = [];
        
        // Adicionar itens do carrinho
        foreach ($cart->items as $item) {
            $mpItem = new MercadoPagoItem();
            $mpItem->title = $item->name;
            $mpItem->quantity = $item->quantity;
            $mpItem->currency_id = $cart->cart_currency_code;
            $mpItem->unit_price = $item->price;
            
            if ($item->product->images->isNotEmpty()) {
                $mpItem->picture_url = $item->product->images->first()->url;
            }
            
            $items[] = $mpItem;
        }
        
        // Adicionar frete como item separado
        if ($cart->shipping_amount > 0) {
            $shippingItem = new MercadoPagoItem();
            $shippingItem->title = 'Frete';
            $shippingItem->quantity = 1;
            $shippingItem->currency_id = $cart->cart_currency_code;
            $shippingItem->unit_price = $cart->shipping_amount;
            
            $items[] = $shippingItem;
        }
        
        return $items;
    }

    /**
     * Get payer information
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return \MercadoPago\Payer
     */
    protected function getPayer($cart)
    {
        $billing = $cart->billing_address;
        
        $payer = new MercadoPagoPayer();
        $payer->name = $billing->first_name . ' ' . $billing->last_name;
        $payer->email = $cart->customer_email;
        $payer->phone = [
            'area_code' => substr(preg_replace('/\D/', '', $billing->phone), 0, 2),
            'number' => substr(preg_replace('/\D/', '', $billing->phone), 2)
        ];
        
        $payer->address = [
            'street_name' => $billing->address1,
            'street_number' => $billing->address2,
            'zip_code' => $billing->postcode,
            'city' => $billing->city,
            'federal_unit' => $billing->state
        ];
        
        return $payer;
    }

    /**
     * Get payment method image.
     *
     * @return string
     */
    public function getImage()
    {
        return bagisto_asset('images/mercadopago.png', 'shop');
    }

    /**
     * Process payment
     *
     * @param  array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment($data)
    {
        try {
            $payment = new MercadoPagoPayment();
            $payment->transaction_amount = $data['transaction_amount'];
            $payment->token = $data['token'];
            $payment->description = $data['description'];
            $payment->installments = $data['installments'] ?? 1;
            $payment->payment_method_id = $data['payment_method_id'];
            $payment->payer = [
                'email' => $data['payer']['email'],
                'identification' => [
                    'type' => $data['payer']['identification']['type'],
                    'number' => $data['payer']['identification']['number']
                ]
            ];
            
            $payment->save();
            
            return [
                'success' => in_array($payment->status, ['approved', 'in_process']),
                'status' => $payment->status,
                'message' => $payment->status_detail,
                'payment_id' => $payment->id
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get payment status
     *
     * @param  string  $paymentId
     * @return array
     */
    public function getPaymentStatus($paymentId)
    {
        try {
            $payment = MercadoPagoPayment::find_by_id($paymentId);
            
            return [
                'success' => true,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'payment' => $payment->toArray()
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Process webhook notification
     *
     * @param  array  $data
     * @return array
     */
    public function processWebhook($data)
    {
        try {
            $paymentId = $data['data']['id'] ?? null;
            
            if (! $paymentId) {
                throw new \Exception('ID do pagamento não encontrado no webhook');
            }
            
            $payment = MercadoPagoPayment::find_by_id($paymentId);
            
            if (! $payment) {
                throw new \Exception('Pagamento não encontrado: ' . $paymentId);
            }
            
            return [
                'success' => true,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'payment_id' => $payment->id,
                'external_reference' => $payment->external_reference
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
