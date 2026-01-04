<?php

namespace Webkul\MercadoPago\Payment;

use Webkul\Payment\Payment\Payment;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Payment as MercadoPagoPayment;

class MercadoPago extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'mercadopago';

    /**
     * Check if payment method is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        // Check if payment method is active
        if (! parent::isAvailable()) {
            return false;
        }

        // Check if required credentials are configured
        $publicKey = $this->getConfigData('public_key');
        $accessToken = $this->getConfigData('access_token');

        if (empty($publicKey) || empty($accessToken)) {
            return false;
        }

        return true;
    }

    /**
     * Mercado Pago SDK instance
     *
     * @var mixed
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
            MercadoPagoConfig::setAccessToken($accessToken);
            MercadoPagoConfig::setIntegratorId('bagisto_mp');
            
            // Usar constantes corretas do SDK
            if ($this->getConfigData('sandbox')) {
                MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
            } else {
                MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::SERVER);
            }
        }
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

        try {
            $preference = $this->createPreference($this->cart);
            
            if (! $preference) {
                return false;
            }
            
            return $this->getConfigData('sandbox') 
                ? ($preference->sandbox_init_point ?? $preference->init_point)
                : $preference->init_point;
                
        } catch (\Exception $e) {
            // Log error and return false to prevent checkout issues
            \Log::error('Mercado Pago getRedirectUrl error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a preference in Mercado Pago
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return \MercadoPago\Resources\Preference|null
     */
    protected function createPreference($cart)
    {
        try {
            $client = new PreferenceClient();
            
            // Configurar itens do carrinho
            $items = $this->getItems($cart);
            
            // Configurar pagador
            $payer = $this->getPayer($cart);
            
            // Criar preferência como array (SDK espera array)
            $preference = [
                'items' => $items,
                'payer' => $payer,
                'back_urls' => [
                    'success' => route('mercadopago.success'),
                    'failure' => route('mercadopago.failure'),
                    'pending' => route('mercadopago.pending'),
                ],
                'auto_return' => 'approved',
                'external_reference' => $cart->id,
                'notification_url' => route('mercadopago.webhook'),
            ];
            
            $result = $client->create($preference);
            
            return $result;
            
        } catch (\Exception $e) {
            \Log::error('Mercado Pago createPreference error: ' . $e->getMessage());
            return null;
        }
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
            $mpItem = [
                'title' => $item->name,
                'quantity' => $item->quantity,
                'currency_id' => $cart->cart_currency_code,
                'unit_price' => (float) $item->price,
            ];
            
            if ($item->product->images->isNotEmpty()) {
                $mpItem['picture_url'] = $item->product->images->first()->url;
            }
            
            $items[] = $mpItem;
        }
        
        // Adicionar frete como item separado
        if ($cart->shipping_amount > 0) {
            $shippingItem = [
                'title' => 'Frete',
                'quantity' => 1,
                'currency_id' => $cart->cart_currency_code,
                'unit_price' => (float) $cart->shipping_amount,
            ];
            
            $items[] = $shippingItem;
        }
        
        return $items;
    }

    /**
     * Get payer information
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return array
     */
    protected function getPayer($cart)
    {
        $billing = $cart->billing_address;
        
        $payer = [
            'name' => $billing->first_name . ' ' . $billing->last_name,
            'email' => $cart->customer_email,
            'phone' => [
                'area_code' => substr(preg_replace('/\D/', '', $billing->phone), 0, 2),
                'number' => substr(preg_replace('/\D/', '', $billing->phone), 2)
            ],
            'address' => [
                'street_name' => $billing->address1,
                'street_number' => $billing->address2,
                'zip_code' => $billing->postcode,
                'city' => $billing->city,
                'federal_unit' => $billing->state
            ]
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
            
            $client = new PaymentClient();
            $payment = $client->get($paymentId);
            
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
