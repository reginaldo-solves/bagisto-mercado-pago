@extends('shop::layouts.master')

@section('page_title')
    Pagamento Não Aprovado
@stop

@section('content-wrapper')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="payment-failure">
                    <div class="failure-icon">
                        <i class="bi bi-x-circle-fill text-danger"></i>
                    </div>
                    <h1>Pagamento Não Aprovado</h1>
                    <p>Não foi possível processar seu pagamento. Por favor, tente novamente ou escolha outra forma de pagamento.</p>
                    
                    <div class="order-details">
                        <h3>Detalhes do Pedido</h3>
                        <div class="detail-item">
                            <span>Número do pedido:</span>
                            <span>{{ $order->id }}</span>
                        </div>
                        <div class="detail-item">
                            <span>Valor:</span>
                            <span>{{ $order->formatted_grand_total }}</span>
                        </div>
                    </div>
                    
                    <div class="actions">
                        <a href="{{ route('shop.checkout.cart.index') }}" class="btn btn-primary">
                            Tentar Novamente
                        </a>
                        <a href="{{ route('shop.home.index') }}" class="btn btn-secondary">
                            Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .payment-failure {
                max-width: 600px;
                margin: 2rem auto;
                padding: 2rem;
                text-align: center;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            
            .failure-icon {
                font-size: 4rem;
                margin-bottom: 1rem;
            }
            
            .payment-failure h1 {
                color: #dc3545;
                margin-bottom: 1rem;
            }
            
            .order-details {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 1.5rem;
                margin: 2rem 0;
                text-align: left;
            }
            
            .detail-item {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.5rem;
            }
            
            .detail-item:last-child {
                margin-bottom: 0;
            }
            
            .actions {
                display: flex;
                gap: 1rem;
                justify-content: center;
            }
        </style>
    @endpush
@endsection
