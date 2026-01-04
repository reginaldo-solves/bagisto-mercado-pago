@extends('shop::layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <img src="{{ bagisto_asset('images/mercadopago.png', 'shop') }}" 
                             alt="Mercado Pago" style="height: 30px; margin-right: 10px;">
                        Pagar com Mercado Pago
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Resumo do Pedido -->
                    <div class="order-summary mb-4">
                        <h5>Resumo do Pedido</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Qtd</th>
                                        <th>Preço</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ core()->currency($item->price) }}</td>
                                        <td>{{ core()->currency($item->total) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Subtotal:</th>
                                        <td>{{ core()->currency($cart->sub_total) }}</td>
                                    </tr>
                                    @if($cart->shipping_amount > 0)
                                    <tr>
                                        <th colspan="3">Frete:</th>
                                        <td>{{ core()->currency($cart->shipping_amount) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="table-primary">
                                        <th colspan="3">Total:</th>
                                        <th>{{ core()->currency($cart->grand_total) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Formulário de Pagamento -->
                    <form id="mercadopago-payment-form">
                        @csrf
                        <input type="hidden" name="transaction_amount" value="{{ $cart->grand_total }}">
                        
                        <div class="row">
                            <!-- Dados do Cartão -->
                            <div class="col-md-6">
                                <h6>Dados do Cartão</h6>
                                
                                <div class="form-group mb-3">
                                    <label for="cardNumber">Número do Cartão</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control" 
                                               id="cardNumber" 
                                               data-checkout="cardNumber"
                                               placeholder="0000 0000 0000 0000"
                                               required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="card-brand"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="cardExpirationMonth">Validade</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-control" 
                                                    id="cardExpirationMonth" 
                                                    data-checkout="cardExpirationMonth"
                                                    required>
                                                <option value="">Mês</option>
                                                @for($month = 1; $month <= 12; $month++)
                                                <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                                    {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}
                                                </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-control" 
                                                    id="cardExpirationYear" 
                                                    data-checkout="cardExpirationYear"
                                                    required>
                                                <option value="">Ano</option>
                                                @for($year = date('Y'); $year <= date('Y') + 10; $year++)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="securityCode">CVV</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="securityCode" 
                                           data-checkout="securityCode"
                                           placeholder="123"
                                           maxlength="4"
                                           required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="cardholderName">Nome no Cartão</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="cardholderName" 
                                           data-checkout="cardholderName"
                                           placeholder="Nome como está no cartão"
                                           required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="docType">Tipo de Documento</label>
                                    <select class="form-control" 
                                            id="docType" 
                                            data-checkout="docType"
                                            required>
                                        <option value="">Selecione</option>
                                        <option value="CPF">CPF</option>
                                        <option value="CNPJ">CNPJ</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="docNumber">Número do Documento</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="docNumber" 
                                           data-checkout="docNumber"
                                           placeholder="000.000.000-00"
                                           required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email">E-mail</label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email"
                                           value="{{ auth()->guard('customer')->user()->email ?? '' }}"
                                           placeholder="seu@email.com"
                                           required>
                                </div>
                            </div>

                            <!-- Parcelas e Pagamento -->
                            <div class="col-md-6">
                                <h6>Opções de Pagamento</h6>
                                
                                <div class="form-group mb-3">
                                    <label for="installments">Parcelas</label>
                                    <select class="form-control" 
                                            id="installments" 
                                            name="installments"
                                            required>
                                        <option value="">Carregando...</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="paymentMethodId">Método de Pagamento</label>
                                    <input type="hidden" id="paymentMethodId" name="payment_method_id">
                                    <div class="alert alert-info" id="paymentMethodInfo">
                                        <i class="fas fa-info-circle"></i>
                                        O método de pagamento será detectado automaticamente
                                    </div>
                                </div>

                                <!-- Opções de Assinatura -->
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="makeRecurring">
                                                <label class="form-check-label" for="makeRecurring">
                                                    Tornar pagamento recorrente
                                                </label>
                                            </div>
                                        </h6>
                                    </div>
                                    <div class="card-body" id="recurringOptions" style="display: none;">
                                        <div class="form-group mb-3">
                                            <label for="recurringFrequency">Frequência</label>
                                            <select class="form-control" id="recurringFrequency">
                                                <option value="monthly">Mensal</option>
                                                <option value="yearly">Anual</option>
                                            </select>
                                        </div>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            Você será cobrado automaticamente a cada 
                                            <span id="frequencyText">mês</span> 
                                            no valor de {{ core()->currency($cart->grand_total) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Botões de Ação -->
                                <div class="d-grid gap-2">
                                    <button type="submit" 
                                            class="btn btn-primary btn-lg" 
                                            id="submitPayment">
                                        <i class="fas fa-lock"></i> Pagar Agora
                                    </button>
                                    
                                    <a href="{{ route('shop.checkout.cart.index') }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> Voltar ao Carrinho
                                    </a>
                                </div>

                                <!-- Informações de Segurança -->
                                <div class="mt-3 text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt"></i>
                                        Pagamento seguro criptografado pelo Mercado Pago
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Processando...</span>
            </div>
            <div class="mt-2">
                <h5>Processando pagamento...</h5>
                <p class="text-muted">Por favor, aguarde um momento</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Mercado Pago SDK
    const mercadopago = new MercadoPago('{{ $publicKey }}', {
        locale: 'pt-BR'
    });

    // Card number formatting and validation
    const cardNumber = document.getElementById('cardNumber');
    const cardBrand = document.getElementById('card-brand');
    const installments = document.getElementById('installments');
    const paymentMethodId = document.getElementById('paymentMethodId');
    const submitButton = document.getElementById('submitPayment');
    const loadingOverlay = document.getElementById('loadingOverlay');

    // Format card number
    cardNumber.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;

        // Get payment methods when 6 digits are entered
        if (value.length >= 6) {
            getPaymentMethods(value.substring(0, 6));
        }
    });

    // Get payment methods and installments
    function getPaymentMethods(bin) {
        fetch('{{ route("mercadopago.payment.methods") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ bin: bin })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.payment_methods.length > 0) {
                const paymentMethod = data.payment_methods[0];
                paymentMethodId.value = paymentMethod.id;
                
                // Update card brand display
                cardBrand.innerHTML = `<img src="${paymentMethod.secure_thumbnail}" alt="${paymentMethod.name}" style="height: 20px;">`;
                
                // Get installments
                getInstallments(paymentMethod.id);
            }
        })
        .catch(error => {
            console.error('Error getting payment methods:', error);
        });
    }

    // Get installments
    function getInstallments(paymentMethodId) {
        const amount = parseFloat(document.querySelector('input[name="transaction_amount"]').value);
        
        mercadopago.getInstallments({
            amount: amount,
            bin: cardNumber.value.replace(/\s/g, '').substring(0, 6),
            payment_method_id: paymentMethodId
        }).then(response => {
            installments.innerHTML = '';
            response.payer_costs.forEach(installment => {
                const option = document.createElement('option');
                option.value = installment.installments;
                option.textContent = `${installment.installments}x de ${installment.recommended_message} (Total: ${installment.total_amount})`;
                installments.appendChild(option);
            });
        });
    }

    // Handle recurring payment toggle
    const makeRecurring = document.getElementById('makeRecurring');
    const recurringOptions = document.getElementById('recurringOptions');
    const frequencyText = document.getElementById('frequencyText');
    const recurringFrequency = document.getElementById('recurringFrequency');

    makeRecurring.addEventListener('change', function() {
        if (this.checked) {
            recurringOptions.style.display = 'block';
        } else {
            recurringOptions.style.display = 'none';
        }
    });

    recurringFrequency.addEventListener('change', function() {
        frequencyText.textContent = this.value === 'monthly' ? 'mês' : 'ano';
    });

    // Form submission
    document.getElementById('mercadopago-payment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading
        loadingOverlay.style.display = 'flex';
        submitButton.disabled = true;

        // Create card token
        const cardToken = {
            cardNumber: cardNumber.value.replace(/\s/g, ''),
            cardholderName: document.getElementById('cardholderName').value,
            cardExpirationMonth: document.getElementById('cardExpirationMonth').value,
            cardExpirationYear: document.getElementById('cardExpirationYear').value,
            securityCode: document.getElementById('securityCode').value,
            identificationType: document.getElementById('docType').value,
            identificationNumber: document.getElementById('docNumber').value
        };

        mercadopago.createCardToken(cardToken)
            .then(token => {
                // Submit payment
                const formData = new FormData(this);
                formData.append('token', token.id);
                formData.append('payment_method_id', paymentMethodId.value);
                formData.append('identification_type', document.getElementById('docType').value);
                formData.append('identification_number', document.getElementById('docNumber').value);

                if (makeRecurring.checked) {
                    formData.append('make_recurring', 'true');
                    formData.append('frequency', recurringFrequency.value);
                }

                fetch('{{ route("mercadopago.payment.process") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert('Erro no pagamento: ' + data.message);
                        loadingOverlay.style.display = 'none';
                        submitButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Payment error:', error);
                    alert('Erro ao processar pagamento. Tente novamente.');
                    loadingOverlay.style.display = 'none';
                    submitButton.disabled = false;
                });
            })
            .catch(error => {
                console.error('Token creation error:', error);
                alert('Erro ao validar cartão. Verifique os dados e tente novamente.');
                loadingOverlay.style.display = 'none';
                submitButton.disabled = false;
            });
    });
});
</script>

<style>
#loadingOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    color: white;
}

.input-group-text img {
    max-height: 20px;
}

.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-control:focus {
    border-color: #00B4D8;
    box-shadow: 0 0 0 0.2rem rgba(0, 180, 216, 0.25);
}

.btn-primary {
    background-color: #00B4D8;
    border-color: #00B4D8;
}

.btn-primary:hover {
    background-color: #0096C7;
    border-color: #0096C7;
}
</style>
@endpush
