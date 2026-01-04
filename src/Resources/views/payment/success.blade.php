@extends('shop::layouts.master')

@section('page_title')
    Pagamento Aprovado
@stop

@section('content-wrapper')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="payment-success">
                    <div class="success-icon">
                        <i class="bi bi-check-circle-fill text-success"></i>
                    </div>
                    <h1>Pagamento Aprovado!</h1>
                    <p>Seu pagamento foi aprovado com sucesso!</p>
                    
                    <div class="order-details">
                        <h3>Detalhes do Pedido</h3>
                        <div class="detail-item">
                            <span>Número do pedido:</span>
                            <span>{{ $order->id }}</span>
                        </div>
                        <div class="detail-item">
                            <span>Valor pago:</span>
                            <span>{{ $order->formatted_grand_total }}</span>
                        </div>
                    </div>
                    
                    <div class="actions">
                        <a href="{{ route('shop.home.index') }}" class="btn btn-primary">
                            Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .payment-success {
                max-width: 600px;
                margin: 2rem auto;
                padding: 2rem;
                text-align: center;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            
            .success-icon {
                font-size: 4rem;
                margin-bottom: 1rem;
            }
            
            .payment-success h1 {
                color: #28a745;
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
        </style>
    @endpush
@endsection
