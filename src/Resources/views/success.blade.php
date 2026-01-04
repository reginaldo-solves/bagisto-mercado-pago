@extends('shop::layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <div class="mb-3">
                        <img src="{{ bagisto_asset('images/mercadopago.png', 'shop') }}" 
                             alt="Mercado Pago" style="height: 50px;">
                    </div>
                    <h3 class="text-success">
                        <i class="fas fa-check-circle"></i> Pagamento Realizado com Sucesso!
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('order_id'))
                    <div class="alert alert-success">
                        <h5><i class="fas fa-shopping-cart"></i> Pedido #{{ session('order_id') }}</h5>
                        <p class="mb-0">Seu pedido foi processado com sucesso através do Mercado Pago.</p>
                    </div>
                    @endif

                    @if(session('subscription_id'))
                    <div class="alert alert-info">
                        <h5><i class="fas fa-sync-alt"></i> Assinatura Criada</h5>
                        <p class="mb-0">
                            Sua assinatura recorrente foi configurada com sucesso. 
                            ID da Assinatura: {{ session('subscription_id') }}
                        </p>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle"></i> Informações da Assinatura
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Frequência:</strong> 
                                        {{ session('frequency') == 'monthly' ? 'Mensal' : 'Anual' }}
                                    </p>
                                    <p><strong>Valor:</strong> {{ core()->currency(session('amount')) }}</p>
                                    <p><strong>Próxima Cobrança:</strong> {{ session('next_charge_date') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-success">Ativa</span>
                                    </p>
                                    <p><strong>Método de Pagamento:</strong> Cartão de Crédito</p>
                                    <p><strong>ID da Assinatura:</strong> {{ session('subscription_id') }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <h6>Gerenciar Assinatura</h6>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('customer.subscriptions.index') }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-list"></i> Minhas Assinaturas
                                    </a>
                                    <a href="{{ route('customer.subscriptions.view', session('subscription_id')) }}" 
                                       class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i> Ver Detalhes
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="confirmCancelSubscription('{{ session('subscription_id') }}')">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-receipt"></i> Resumo da Transação
                            </h6>
                        </div>
                        <div class="card-body">
                            @if(session('payment_id'))
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>ID do Pagamento:</strong> {{ session('payment_id') }}</p>
                                    <p><strong>Data:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                                    <p><strong>Método:</strong> Cartão de Crédito</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-success">Aprovado</span>
                                    </p>
                                    <p><strong>Valor:</strong> {{ core()->currency(session('amount')) }}</p>
                                    <p><strong>Moeda:</strong> BRL</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-envelope"></i> Confirmação por E-mail</h6>
                        <p class="mb-0">
                            Enviamos um e-mail de confirmação para {{ auth()->guard('customer')->user()->email }} 
                            com todos os detalhes da transação e informações da assinatura.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="btn-group-vertical" role="group">
                            <a href="{{ route('customer.orders.index') }}" 
                               class="btn btn-primary btn-lg mb-2">
                                <i class="fas fa-list"></i> Ver Meus Pedidos
                            </a>
                            
                            @if(session('subscription_id'))
                            <a href="{{ route('customer.subscriptions.index') }}" 
                               class="btn btn-outline-primary mb-2">
                                <i class="fas fa-sync-alt"></i> Gerenciar Assinaturas
                            </a>
                            @endif
                            
                            <a href="{{ route('shop.home.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-home"></i> Continuar Comprando
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Cancelamento -->
<div class="modal fade" id="cancelSubscriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancelar Assinatura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja cancelar sua assinatura?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Atenção:</strong> Ao cancelar, você não será mais cobrado, 
                    mas poderá perder acesso a benefícios ativos.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="fas fa-times"></i> Confirmar Cancelamento
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmCancelSubscription(subscriptionId) {
    const modal = new bootstrap.Modal(document.getElementById('cancelSubscriptionModal'));
    modal.show();
    
    document.getElementById('confirmCancelBtn').onclick = function() {
        fetch(`{{ route('mercadopago.recurring.cancel') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ subscription_id: subscriptionId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.hide();
                alert('Assinatura cancelada com sucesso!');
                location.reload();
            } else {
                alert('Erro ao cancelar assinatura: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao cancelar assinatura. Tente novamente.');
        });
    };
}
</script>
@endpush
