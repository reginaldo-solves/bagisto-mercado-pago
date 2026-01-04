<?php

namespace Webkul\MercadoPago\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\MercadoPago\Payment\MercadoPago;

class FrontendController extends Controller
{
    /**
     * Mercado Pago payment instance
     *
     * @var \Webkul\MercadoPago\Payment\MercadoPago
     */
    protected $mercadopago;

    /**
     * Order repository instance
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Constructor
     *
     * @param \Webkul\MercadoPago\Payment\MercadoPago $mercadopago
     * @param \Webkul\Sales\Repositories\OrderRepository $orderRepository
     */
    public function __construct(
        MercadoPago $mercadopago,
        OrderRepository $orderRepository
    ) {
        $this->mercadopago = $mercadopago;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Show payment form with Mercado Pago SDK
     *
     * @return \Illuminate\View\View
     */
    public function showPaymentForm()
    {
        $cart = Cart::getCart();
        
        if (!$cart) {
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'Carrinho não encontrado');
        }

        // Get Mercado Pago configuration
        $publicKey = $this->mercadopago->getConfigData('public_key');
        $sandbox = $this->mercadopago->getConfigData('sandbox', true);

        return view('mercadopago::checkout.payment-form', compact(
            'cart',
            'publicKey',
            'sandbox'
        ));
    }

    /**
     * Process payment with card token
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'payment_method_id' => 'required|string',
                'installments' => 'required|integer|min:1',
                'identification_type' => 'required|string',
                'identification_number' => 'required|string',
                'email' => 'required|email',
            ]);

            $cart = Cart::getCart();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Carrinho não encontrado'
                ], 400);
            }

            // Prepare payment data
            $paymentData = [
                'token' => $request->token,
                'transaction_amount' => (float) $cart->grand_total,
                'description' => 'Pedido #' . $cart->id,
                'installments' => $request->installments,
                'payment_method_id' => $request->payment_method_id,
                'payer' => [
                    'email' => $request->email,
                    'identification' => [
                        'type' => $request->identification_type,
                        'number' => $request->identification_number
                    ]
                ]
            ];

            // Process payment
            $result = $this->mercadopago->processPayment($paymentData);

            if ($result['success']) {
                // Create order
                $order = $this->orderRepository->create(Cart::prepareDataForOrder());

                // Add payment information
                $order->payment_method = 'mercadopago';
                $order->payment_id = $result['payment_id'];
                $order->payment_status = $result['status'];
                $order->save();

                // Clear cart
                Cart::deActivateCart();

                return response()->json([
                    'success' => true,
                    'message' => 'Pagamento processado com sucesso',
                    'order_id' => $order->id,
                    'redirect_url' => route('mercadopago.success')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Falha no processamento do pagamento'
                ], 400);
            }

        } catch (\Exception $e) {
            \Log::error('Mercado Pago payment error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar pagamento. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Get payment methods from Mercado Pago
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentMethods(Request $request)
    {
        try {
            $bin = $request->get('bin');
            
            if (!$bin) {
                return response()->json([
                    'success' => false,
                    'message' => 'BIN do cartão não fornecido'
                ], 400);
            }

            // Get payment methods for the BIN
            $paymentMethods = $this->mercadopago->getPaymentMethods($bin);

            return response()->json([
                'success' => true,
                'payment_methods' => $paymentMethods
            ]);

        } catch (\Exception $e) {
            \Log::error('Mercado Pago getPaymentMethods error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter métodos de pagamento'
            ], 500);
        }
    }

    /**
     * Create recurring payment plan
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRecurringPlan(Request $request)
    {
        try {
            $request->validate([
                'plan_name' => 'required|string|max:255',
                'plan_description' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0.01',
                'frequency' => 'required|in:monthly,yearly',
                'currency' => 'required|string|size:3'
            ]);

            $planData = [
                'reason' => $request->plan_name,
                'auto_recurring' => [
                    'frequency' => $request->frequency === 'monthly' ? 1 : 12,
                    'frequency_type' => 'months',
                    'transaction_amount' => (float) $request->amount,
                    'currency_id' => $request->currency
                ],
                'back_url' => route('mercadopago.success'),
                'external_reference' => 'plan_' . time()
            ];

            $result = $this->mercadopago->createRecurringPlan($planData);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'plan_id' => $result->id,
                    'init_point' => $result->init_point
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Falha ao criar plano recorrente'
                ], 400);
            }

        } catch (\Exception $e) {
            \Log::error('Mercado Pago createRecurringPlan error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar plano recorrente'
            ], 500);
        }
    }

    /**
     * Subscribe customer to recurring plan
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        try {
            $request->validate([
                'plan_id' => 'required|string',
                'token' => 'required|string',
                'payer_email' => 'required|email',
                'card_token' => 'required|string'
            ]);

            $subscriptionData = [
                'plan_id' => $request->plan_id,
                'token' => $request->token,
                'payer' => [
                    'email' => $request->payer_email
                ],
                'card_token_id' => $request->card_token
            ];

            $result = $this->mercadopago->createSubscription($subscriptionData);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'subscription_id' => $result->id,
                    'status' => $result->status
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Falha ao criar assinatura'
                ], 400);
            }

        } catch (\Exception $e) {
            \Log::error('Mercado Pago subscribe error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar assinatura'
            ], 500);
        }
    }
}
