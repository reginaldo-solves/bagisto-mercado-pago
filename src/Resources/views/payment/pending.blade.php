@extends('shop::layouts.master')

@section('page_title')
    Pagamento em Análise
@stop

@section('content-wrapper')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="payment-pending">
                    <div class="pending-icon">
                        <i class="bi bi-clock-fill text-warning"></i>
                    </div>
                    <h1>Pagamento em Análise</h1>
                    <p>Seu pagamento está sendo processado. Você receberá uma confirmação por e-mail assim que for aprovado.</p>
                    
                    <div class="instructions">
                        <h3>Instruções</h3>
                        <p>Se você pagou com boleto, o prazo para compensação é de até 3 dias úteis.</p>
                        <p>Em caso de dúvidas, entre em contato com nosso suporte.</p>
                    </div>
                    
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
            .payment-pending {
                max-width: 600px;
                margin: 2rem auto;
                padding: 2rem;
                text-align: center;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            
            .pending-icon {
                font-size: 4rem;
                margin-bottom: 1rem;
            }
            
            .payment-pending h1 {
                color: #ffc107;
                margin-bottom: 1rem;
            }
            
            .instructions {
                background: #fff3cd;
                border: 1px solid #ffeaa7;
                border-radius: 8px;
                padding: 1.5rem;
                margin: 2rem 0;
                text-align: left;
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
