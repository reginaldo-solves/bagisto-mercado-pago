# Especificação de Funcionalidade: Método de Pagamento Mercado Pago

**Feature Branch**: `001-mercado-pago`  
**Created**: 2025-01-03  
**Status**: Draft  
**Environment**: Docker (bagisto-docker)  
**Input**: User description: "Criar um método de pagamento Mercado Pago para Bagisto seguindo a documentação oficial: https://devdocs.bagisto.com/payment-method-development/getting-started.html https://devdocs.bagisto.com/payment-method-development/create-your-first-payment-method.html https://devdocs.bagisto.com/payment-method-development/understanding-payment-configuration.html https://devdocs.bagisto.com/payment-method-development/understanding-payment-class.html O método deve: Ser criado como package em packages/Reginaldo/MercadoPago Suportar Pix, Cartão e Boleto (separáveis por config) Usar API oficial do Mercado Pago (Brasil) Ter configurações no admin do Bagisto Não alterar código core do Bagisto"

## Cenários de Usuário & Testes *(obrigatório)*

### User Story 1 - Configuração do Método de Pagamento (Prioridade: P1)

Administrador pode configurar o método de pagamento Mercado Pago no painel admin do Bagisto, habilitando/desabilitando os métodos Pix, Cartão de Crédito e Boleto, e configurando credenciais da API.

**Por que esta prioridade**: Essencial para que o método funcione - sem configuração não há processamento de pagamentos.

**Teste Independente**: Pode ser totalmente testado pelo acesso ao painel admin, configuração das credenciais e seleção dos métodos de pagamento, verificando que as configurações são salvas e persistidas.

**Cenários de Aceitação**:

1. **Dado** que sou um administrador logado no painel admin, **Quando** acesso a configuração de métodos de pagamento, **Então** vejo o Mercado Pago disponível para configuração
2. **Dado** que estou na página de configuração do Mercado Pago, **Quando** preencho as credenciais da API e salvo, **Então** as configurações são persistidas e o método fica ativo
3. **Dado** que o Mercado Pago está configurado, **Quando** desabilito o pagamento com Cartão, **Então** apenas Pix e Boleto ficam disponíveis no checkout

---

### User Story 2 - Processamento de Pagamento Pix (Prioridade: P1)

Cliente pode selecionar Pix como método de pagamento, gerar QR Code para pagamento e confirmar o pagamento automaticamente via webhook do Mercado Pago.

**Por que esta prioridade**: Pix é o método mais popular no Brasil e essencial para e-commerce moderno.

**Teste Independente**: Pode ser totalmente testado pela simulação de um checkout completo com Pix, geração do QR Code e confirmação via webhook.

**Cenários de Aceitação**:

1. **Dado** que estou no checkout, **Quando** seleciono Pix como método de pagamento, **Então** vejo o QR Code para pagamento
2. **Dado** que o QR Code foi gerado, **Quando** o Mercado Pago envia webhook de confirmação, **Então** o pedido é marcado como pago automaticamente
3. **Dado** que o pagamento Pix foi confirmado, **Quando** verifico o status do pedido, **Então** ele aparece como "Pago" com a referência do Mercado Pago

---

### User Story 3 - Processamento de Cartão de Crédito (Prioridade: P2)

Cliente pode pagar com cartão de crédito usando o checkout transparente do Mercado Pago, com processamento seguro e retorno automático do status.

**Por que esta prioridade**: Cartão de crédito ainda é um método amplamente utilizado, essencial para maximizar conversões.

**Teste Independente**: Pode ser totalmente testado pela simulação de pagamento com cartão de crédito em ambiente de teste do Mercado Pago.

**Cenários de Aceitação**:

1. **Dado** que estou no checkout, **Quando** seleciono Cartão de Crédito, **Então** vejo o formulário seguro de dados do cartão
2. **Dado** que preenchi os dados do cartão válidos, **Quando** confirmo o pagamento, **Então** o pagamento é processado e o pedido é atualizado
3. **Dado** que o pagamento foi recusado, **Quando** verifico o status, **Então** vejo mensagem de erro clara e posso tentar outro método

---

### User Story 4 - Processamento de Boleto (Prioridade: P3)

Cliente pode gerar boleto bancário via Mercado Pago, receber o PDF para impressão e ter o pagamento confirmado automaticamente após vencimento.

**Por que esta prioridade**: Boleto ainda é relevante para clientes corporativos e quem prefere pagamento parcelado.

**Teste Independente**: Pode ser totalmente testado pela geração de boleto e simulação de confirmação via webhook.

**Cenários de Aceitação**:

1. **Dado** que estou no checkout, **Quando** seleciono Boleto, **Então** recebo o boleto em PDF para download/impressão
2. **Dado** que o boleto foi gerado, **Quando** acesso meus pedidos, **Então** vejo o código de barras e data de vencimento
3. **Dado** que o boleto foi pago, **Quando** o Mercado Pago envia webhook, **Então** o pedido é marcado como pago

---

### Casos de Borda

- O que acontece quando a API do Mercado Pago está indisponível?
- Como o sistema lida com timeouts durante o processamento de pagamento?
- O que acontece quando as credenciais da API estão incorretas?
- Como o sistema lida com webhooks duplicados do Mercado Pago?
- O que acontece quando o cliente fecha o browser durante o pagamento?

## Requisitos *(obrigatório)*

### Requisitos Funcionais

- **FR-001**: Sistema DEVE criar pacote em `packages/Reginaldo/MercadoPago` seguindo arquitetura de pagamento do Bagisto
- **FR-002**: Sistema DEVE suportar configuração de credenciais (Access Token) da API Mercado Pago Brasil
- **FR-003**: Sistema DEVE permitir habilitar/desabilitar individualmente Pix, Cartão e Boleto via configuração admin
- **FR-004**: Sistema DEVE processar pagamentos Pix via API oficial Mercado Pago com geração de QR Code
- **FR-005**: Sistema DEVE processar pagamentos com cartão via checkout transparente Mercado Pago
- **FR-006**: Sistema DEVE gerar boletos via API Mercado Pago com PDF para download
- **FR-007**: Sistema DEVE receber e processar webhooks do Mercado Pago para confirmação automática
- **FR-008**: Sistema DEVE registrar todas as transações com referência do Mercado Pago
- **FR-009**: Sistema DEVE tratar erros da API de forma amigável para o usuário
- **FR-010**: Sistema DEVE manter compatibilidade com atualizações futuras do Bagisto

### Entidades Principais

- **Configuração Mercado Pago**: Credenciais da API, métodos habilitados, configurações por canal
- **Transação Pagamento**: Referência Mercado Pago, valor, status, método, dados do pedido
- **Webhook Event**: Tipo do evento, dados recebidos, status de processamento

## Critérios de Sucesso *(obrigatório)*

### Resultados Mensuráveis

- **SC-001**: Administradores podem configurar o método de pagamento em menos de 5 minutos
- **SC-002**: Clientes podem completar pagamento Pix em menos de 2 minutos
- **SC-003**: Taxa de sucesso de pagamentos processados atinge 95% em ambiente de produção
- **SC-004**: Webhooks são processados em menos de 30 segundos após recebimento
- **SC-005**: Zero modificações no código core do Bagisto são necessárias
