# Guia de Configuração - Mercado Pago para Bagisto

## 📋 Configuração dos Campos

### 🏷️ **Título**
- **O que é**: Nome que aparecerá para os clientes no checkout
- **Recomendação**: "Mercado Pago"
- **Exemplo**: `Mercado Pago`

### 📝 **Descrição**  
- **O que é**: Texto explicativo para os clientes sobre o método de pagamento
- **Recomendação**: Descrição clara e concisa
- **Exemplo**: `Pague com Mercado Pago - Aceitamos Pix, Cartão de Crédito e Boleto`

### 🖼️ **Logotipo**
- **O que é**: Imagem que aparecerá no checkout
- **Especificação**: Resolução de 55px X 45px
- **Formatos**: BMP, JPEG, JPG, PNG, WebP
- **Dica**: Use o logo oficial do Mercado Pago

### 🔑 **Chave Pública (Public Key)**
- **O que é**: Chave pública para identificar sua conta no Mercado Pago
- **Onde encontrar**: 
  1. Acesse [mercadopago.com.br/developers](https://mercadopago.com.br/developers)
  2. Vá em "Credenciais"
  3. Copie a "Chave Pública"
- **Ambiente de Teste**: Use chaves de teste (sandbox)
- **Produção**: Use chaves de produção

### 🔐 **Token de Acesso (Access Token)**
- **O que é**: Token privado para autenticar requisições à API
- **Onde encontrar**:
  1. Acesse [mercadopago.com.br/developers](https://mercadopago.com.br/developers)
  2. Vá em "Credenciais" 
  3. Copie o "Token de Acesso"
- **⚠️ Importante**: Mantenha este token em segredo!

### 🌐 **URL do Webhook**
- **O que é**: Endereço para receber notificações de pagamento
- **Formato**: URL completa do seu site
- **Exemplo**: `https://sualoja.com/mercado-pago/webhook`
- **Requisitos**: 
  - HTTPS obrigatório
  - Acessível publicamente
  - Sem bloqueios de firewall

### 🔒 **Segredo do Webhook (Webhook Secret)**
- **O que é**: Chave para validar que notificações vêm do Mercado Pago
- **Onde encontrar**: 
  1. Painel do Mercado Pago
  2. Configurações de Webhooks
  3. Gerar uma chave secreta
- **Importância**: Segurança adicional (opcional mas recomendado)

### ✅ **Status**
- **O que é**: Ativa ou desativa o método de pagamento
- **Opções**: 
  - ✅ Ativado: Método disponível para clientes
  - ❌ Desativado: Método oculto

### 🧪 **Ambiente de Teste**
- **O que é**: Define se usa ambiente de teste ou produção
- **Opções**:
  - ✅ Ativado: Sandbox (testes com dinheiro fictício)
  - ❌ Desativado: Produção (transações reais)
- **Recomendação**: Mantenha ativado durante testes

### 📊 **Ordem de Classificação**
- **O que é**: Posição do método na lista de pagamentos
- **Opções**: 1 (primeiro) a 5 (último)
- **Dica**: Coloque em posição de destaque se for principal

---

## 🔧 **Passo a Passo para Configuração**

### 1. **Obter Credenciais do Mercado Pago**
```bash
# Acesse o site do Mercado Pago Developers
https://mercadopago.com.br/developers

# Crie sua aplicação e obtenha:
# - Chave Pública (Public Key)
# - Token de Acesso (Access Token)
```

### 2. **Configurar Webhook**
```bash
# No painel do Mercado Pago, configure:
URL: https://sualoja.com/mercado-pago/webhook
Eventos: 
- payment_approved
- payment_rejected  
- payment_pending
```

### 3. **Preencher Configuração no Bagisto**
1. Acesse: `/admin/configuration/sales/payment_methods`
2. Encontre "Mercado Pago"
3. Preencha todos os campos obrigatórios
4. Salve as configurações

### 4. **Testar Integração**
1. Ative o modo sandbox
2. Faça um pedido de teste
3. Verifique se o pagamento é processado
4. Confirme as notificações do webhook

---

## ⚠️ **Considerações Importantes**

### **Segurança**
- Nunca compartilhe seu Token de Acesso
- Use HTTPS em produção
- Configure o Webhook Secret

### **Testes**
- Use sempre o ambiente de sandbox primeiro
- Teste todos os métodos (Pix, Cartão, Boleto)
- Verifique as notificações

### **Produção**
- Desative o modo sandbox
- Use credenciais de produção
- Monitore os logs de erro

---

## 🆘 **Suporte**

### **Links Úteis**
- [Mercado Pago Developers](https://mercadopago.com.br/developers)
- [Documentação da API](https://mercadopago.com.br/developers/pt/docs)
- [Dashboard do Mercado Pago](https://mercadopago.com.br)

### **Problemas Comuns**
- **Webhook não funciona**: Verifique se a URL está acessível
- **Pagamento falha**: Confirme as credenciais e ambiente
- **Erro de autenticação**: Verifique Token de Acesso

---

## 📱 **Exemplo de Configuração Completa**

```
Título: Mercado Pago
Descrição: Pague com Pix, Cartão de Crédito e Boleto
Logotipo: [Logo do Mercado Pago - 55x45px]
Chave Pública: TEST-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxx
Token de Acesso: TEST-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxx
URL Webhook: https://sualoja.com/mercado-pago/webhook
Segredo Webhook: sua-chave-secreta-aqui
Status: ✅ Ativado
Ambiente de Teste: ✅ Ativado (para testes)
Ordem: 1 (primeiro da lista)
```
