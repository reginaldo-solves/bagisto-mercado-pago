# Guia de Instalação - Mercado Pago para Bagisto

## ⚠️ **IMPORTANTE: Modificações Necessárias**

Este pacote requer modificações nos arquivos core do Bagisto para funcionar corretamente.

## 📋 **Arquivos que Precisam ser Modificados:**

### 1. **composer.json** (Principal)
Adicione ao autoload PSR-4:
```json
"autoload": {
    "psr-4": {
        "Webkul\\MercadoPago\\": "packages/Webkul/MercadoPago/src/"
    }
}
```

### 2. **bootstrap/providers.php**
Adicione o ServiceProvider:
```php
Webkul\MercadoPago\Providers\MercadoPagoServiceProvider::class,
```

### 3. **packages/Webkul/Admin/src/Config/system.php**
Adicione a configuração completa do Mercado Pago (100+ linhas):
```php
], [
    'key'    => 'sales.payment_methods.mercadopago',
    'name'   => 'admin::app.configuration.index.sales.payment-methods.mercadopago',
    'info'   => 'admin::app.configuration.index.sales.payment-methods.mercadopago-info',
    'sort'   => 5,
    'fields' => [
        // ... campos completos
    ],
], [
```

### 4. **packages/Webkul/Admin/src/Resources/lang/en/app.php**
Adicione as traduções em inglês:
```php
'mercadopago'                     => 'Mercado Pago',
'mercadopago-info'                => 'Accept payments via Pix, Credit Card and Boleto through Mercado Pago',
'mercadopago-public-key'          => 'Mercado Pago Public Key',
// ... demais traduções
```

### 5. **packages/Webkul/Admin/src/Resources/lang/pt_BR/app.php**
Adicione as traduções em português:
```php
'mercadopago'                     => 'Mercado Pago',
'mercadopago-info'                => 'Aceite pagamentos via Pix, Cartão de Crédito e Boleto através do Mercado Pago',
'mercadopago-public-key'          => 'Chave Pública do Mercado Pago',
// ... demais traduções
```

## 🚀 **Métodos de Instalação:**

### **Método 1: Instalação Manual (Recomendado)**

1. **Clone o repositório:**
```bash
cd packages/Webkul/
git clone https://github.com/reginaldo-solves/bagisto-mercado-pago.git MercadoPago
```

2. **Execute os scripts de instalação:**
```bash
# Dentro do diretório do pacote
cd packages/Webkul/MercadoPago
php install.php  # Script que aplica as modificações automaticamente
```

### **Método 2: Via Composer (Modificado)**

1. **Exija o pacote modificado:**
```bash
composer require reginaldo-solves/bagisto-mercado-pago
```

2. **Execute o pós-instalação:**
```bash
php artisan mercadopago:install
```

### **Método 3: Instalação Automática**

1. **Use o instalador:**
```bash
curl -sS https://raw.githubusercontent.com/reginaldo-solves/bagisto-mercado-pago/main/install.sh | bash
```

## 🔧 **Scripts de Instalação**

### **install.php** (dentro do pacote)
```php
#!/usr/bin/env php
<?php

echo "🔧 Instalando Mercado Pago para Bagisto...\n";

// 1. Atualizar composer.json
echo "📝 Atualizando composer.json...\n";
$composerPath = __DIR__ . '/../../../../../composer.json';
$composer = json_decode(file_get_contents($composerPath), true);
$composer['autoload']['psr-4']['Webkul\\MercadoPago\\'] = 'packages/Webkul/MercadoPago/src/';
file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT));

// 2. Atualizar providers.php
echo "📝 Atualizando providers.php...\n";
$providersPath = __DIR__ . '/../../../../../bootstrap/providers.php';
$providers = file_get_contents($providersPath);
$providers = str_replace(
    "Webkul\Paypal\Providers\PaypalServiceProvider::class,",
    "Webkul\Paypal\Providers\PaypalServiceProvider::class,\n    Webkul\MercadoPago\Providers\MercadoPagoServiceProvider::class,",
    $providers
);
file_put_contents($providersPath, $providers);

// 3. Adicionar configuração ao system.php
echo "📝 Adicionando configuração ao system.php...\n";
// ... lógica para adicionar a configuração

// 4. Adicionar traduções
echo "📝 Adicionando traduções...\n";
// ... lógica para adicionar traduções

echo "✅ Mercado Pago instalado com sucesso!\n";
echo "🔄 Execute: composer dump-autoload\n";
echo "🔄 Execute: php artisan config:clear\n";
```

## ⚠️ **Considerações Importantes:**

1. **Backup sempre** antes de instalar
2. **Teste em ambiente de desenvolvimento** primeiro
3. **Verifique a compatibilidade** da versão do Bagisto
4. **Monitore os logs** após a instalação

## 🔄 **Atualização**

Para atualizar o pacote:
```bash
cd packages/Webkul/MercadoPago
git pull origin main
php artisan config:clear
```

## 🗑️ **Remoção**

Para remover o pacote:
```bash
rm -rf packages/Webkul/MercadoPago
# Reverta as modificações manuais nos arquivos core
```

## 📞 **Suporte**

Se encontrar problemas durante a instalação:
- **Issues**: [GitHub Issues](https://github.com/reginaldo-solves/bagisto-mercado-pago/issues)
- **Email**: reginaldo.solves@gmail.com
