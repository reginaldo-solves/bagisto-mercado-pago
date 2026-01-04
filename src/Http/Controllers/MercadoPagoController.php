<?php

namespace Webkul\MercadoPago\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\MercadoPago\Payment\MercadoPago;

class MercadoPagoController extends Controller
{
    /**
     * MercadoPago payment instance
     *
     * @var \Webkul\MercadoPago\Payment\MercadoPago
     */
    protected $mercadopago;

    /**
     * OrderRepository instance
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Reginaldo\MercadoPago\Payment\MercadoPago  $mercadopago
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(
        MercadoPagoPayment $mercadopago,
        OrderRepository $orderRepository
    ) {
        $this->mercadopago = $mercadopago;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display the payment form
     *
     * @return \Illuminate\View\View
     */
    public function paymentForm()
    {
        if (! Cart::getCart()) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        return view('mercadopago::payment.form', compact('cart'));
    }

    /**
     * Página de sucesso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function success(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = $this->orderRepository->find($orderId);

        if (! $order) {
            return redirect()->route('shop.checkout.cart.index');
        }

        // Limpar carrinho
        if ($cart = Cart::getCart()) {
            Cart::deActivateCart();
        }

        return view('mercadopago::payment.success', compact('order'));
    }

    /**
     * Página de pagamento pendente
     *
     * @return \Illuminate\View\View
     */
    public function pending()
    {
        $orderId = session('mercadopago_pending_order_id');

        if (! $orderId) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $order = $this->orderRepository->find($orderId);

        if (! $order) {
            return redirect()->route('shop.checkout.cart.index');
        }

        return view('mercadopago::payment.pending', compact('order'));
    }

    /**
     * Página de falha no pagamento
     *
     * @return \Illuminate\View\View
     */
    public function failure()
    {
        $orderId = session('mercadopago_failed_order_id');

        if (! $orderId) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $order = $this->orderRepository->find($orderId);

        if (! $order) {
            return redirect()->route('shop.checkout.cart.index');
        }

        return view('mercadopago::payment.failure', compact('order'));
    }

    /**
     * Handle webhook notifications from Mercado Pago
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        try {
            $data = $request->all();
            
            // Processar notificação
            $result = $this->mercadopago->processWebhook($data);
            
            if (! $result['success']) {
                throw new \Exception($result['message']);
            }
            
            // Buscar pedido pelo external_reference (order_id)
            $order = $this->orderRepository->find($result['external_reference']);
            
            if (! $order) {
                throw new \Exception('Pedido não encontrado: ' . $result['external_reference']);
            }
            
            // Atualizar status do pedido com base no status do pagamento
            $this->updateOrderStatus($order, $result);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Atualizar status do pedido com base no status do pagamento
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @param  array  $paymentData
     * @return void
     */
    protected function updateOrderStatus($order, $paymentData)
    {
        switch ($paymentData['status']) {
            case 'approved':
                if ($order->status !== 'processing') {
                    $this->orderRepository->updateOrderStatus($order, 'processing');
                    
                    // Registrar histórico
                    $this->orderRepository->createOrderComment($order, [
                        'comment' => 'Pagamento aprovado no Mercado Pago. ID: ' . $paymentData['payment_id'],
                        'customer_notified' => true
                    ]);
                }
                break;
                
            case 'rejected':
            case 'cancelled':
                if ($order->status !== 'canceled') {
                    $this->orderRepository->cancel($order->id);
                    
                    // Registrar histórico
                    $this->orderRepository->createOrderComment($order, [
                        'comment' => 'Pagamento recusado/cancelado no Mercado Pago. ID: ' . $paymentData['payment_id'],
                        'customer_notified' => true
                    ]);
                }
                break;
                
            case 'pending':
            case 'in_process':
                // Registrar histórico
                $this->orderRepository->createOrderComment($order, [
                    'comment' => 'Pagamento em processamento no Mercado Pago. ID: ' . $paymentData['payment_id'],
                    'customer_notified' => true
                ]);
                break;
        }
    }
}
