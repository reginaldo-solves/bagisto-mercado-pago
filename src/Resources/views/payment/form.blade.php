@extends('shop::layouts.master')

@section('page_title')
    Mercado Pago - Finalizar Compra
@stop

@section('content-wrapper')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="mercadopago-payment-form">
                    <div class="mercadopago-header">
                        <h1>Finalizar com Mercado Pago</h1>
                        <p>Pague com Mercado Pago - Aceitamos Pix, Cartão de Crédito e Boleto</p>
                    </div>

                    <div class="mercadopago-content">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="payment-summary">
                            <h3>Resumo do Pedido</h3>
                            <div class="summary-item">
                                <span>Total:</span>
                                <span>{{ $cart->formatted_grand_total }}</span>
                            </div>
                        </div>

                        <div class="payment-methods">
                            <h3>Escolha a forma de pagamento</h3>
                            
                            <div class="payment-method-option">
                                <input type="radio" name="payment_method" value="pix" id="pix-method" checked>
                                <label for="pix-method">
                                    <i class="bi bi-upc-scan"></i>
                                    <span>Pix</span>
                                    <small>Pague com Pix e tenha a confirmação em segundos</small>
                                </label>
                            </div>

                            <div class="payment-method-option">
                                <input type="radio" name="payment_method" value="credit_card" id="credit-card-method">
                                <label for="credit-card-method">
                                    <i class="bi bi-credit-card"></i>
                                    <span>Cartão de Crédito</span>
                                    <small>Pague com seu cartão de crédito</small>
                                </label>
                            </div>

                            <div class="payment-method-option">
                                <input type="radio" name="payment_method" value="boleto" id="boleto-method">
                                <label for="boleto-method">
                                    <i class="bi bi-upc"></i>
                                    <span>Boleto</span>
                                    <small>Pague seu boleto em qualquer banco ou internet banking</small>
                                </label>
                            </div>
                        </div>

                        <div class="payment-actions">
                            <button type="button" class="btn btn-primary btn-pay" onclick="processPayment()">
                                Pagar Agora
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function processPayment() {
                const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
                
                // Redirecionar para o Mercado Pago
                window.location.href = '{{ route("shop.checkout.onepage.success") }}';
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .mercadopago-payment-form {
                max-width: 600px;
                margin: 2rem auto;
                padding: 2rem;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            
            .mercadopago-header {
                text-align: center;
                margin-bottom: 2rem;
            }
            
            .mercadopago-header h1 {
                color: #009ee3;
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }
            
            .payment-summary {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 1.5rem;
                margin-bottom: 2rem;
            }
            
            .summary-item {
                display: flex;
                justify-content: space-between;
                font-size: 1.1rem;
                font-weight: 600;
            }
            
            .payment-method-option {
                display: flex;
                align-items: center;
                padding: 1rem;
                border: 1px solid #e9ecef;
                border-radius: 8px;
                margin-bottom: 1rem;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            
            .payment-method-option:hover {
                border-color: #009ee3;
                background-color: rgba(0, 158, 227, 0.05);
            }
            
            .payment-method-option input[type="radio"] {
                margin-right: 1rem;
            }
            
            .payment-method-option label {
                display: flex;
                align-items: center;
                margin: 0;
                cursor: pointer;
            }
            
            .payment-method-option i {
                font-size: 1.5rem;
                margin-right: 1rem;
                color: #009ee3;
            }
            
            .payment-method-option span {
                font-weight: 600;
                margin-right: 0.5rem;
            }
            
            .payment-method-option small {
                color: #6c757d;
                margin-left: 1rem;
            }
            
            .payment-actions {
                text-align: center;
                margin-top: 2rem;
            }
            
            .btn-pay {
                background: #009ee3;
                color: white;
                font-weight: 600;
                padding: 0.75rem 2rem;
                border-radius: 50px;
                transition: all 0.3s ease;
                border: none;
            }
            
            .btn-pay:hover {
                background: #0088cc;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }
        </style>
    @endpush
@endsection
