#!/usr/bin/env php
<?php

/**
 * Mercado Pago Installer for Bagisto
 * 
 * This script automatically installs Mercado Pago payment method
 * by modifying the necessary Bagisto core files.
 */

echo "🚀 Mercado Pago Installer for Bagisto\n";
echo "=====================================\n\n";

// Check if running in Bagisto environment
if (!file_exists('artisan')) {
    echo "❌ Error: Please run this script from Bagisto root directory\n";
    exit(1);
}

echo "📋 Step 1: Updating composer.json...\n";
updateComposerJson();

echo "📋 Step 2: Updating service providers...\n";
updateProviders();

echo "📋 Step 3: Adding admin configuration...\n";
updateSystemConfig();

echo "📋 Step 4: Adding translations...\n";
updateTranslations();

echo "📋 Step 5: Running post-install commands...\n";
runPostInstallCommands();

echo "\n✅ Mercado Pago installed successfully!\n";
echo "\n🔄 Next steps:\n";
echo "   1. composer dump-autoload\n";
echo "   2. php artisan config:clear\n";
echo "   3. php artisan migrate --path=packages/Webkul/MercadoPago/database/migrations\n";
echo "   4. Access admin panel to configure\n";
echo "\n🎯 Installation complete!\n";

/**
 * Update composer.json to add Mercado Pago autoload.
 */
function updateComposerJson()
{
    $composerPath = 'composer.json';
    
    if (!file_exists($composerPath)) {
        echo "❌ Error: composer.json not found\n";
        return false;
    }

    $composer = json_decode(file_get_contents($composerPath), true);
    
    // Check if already added
    if (isset($composer['autoload']['psr-4']['Webkul\\MercadoPago\\'])) {
        echo "ℹ️  Mercado Pago autoload already exists\n";
        return true;
    }

    // Add autoload
    $composer['autoload']['psr-4']['Webkul\\MercadoPago\\'] = 'packages/Webkul/MercadoPago/src/';
    
    file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    echo "✅ Added Mercado Pago to composer.json\n";
    
    return true;
}

/**
 * Update bootstrap/providers.php to add Mercado Pago service provider.
 */
function updateProviders()
{
    $providersPath = 'bootstrap/providers.php';
    
    if (!file_exists($providersPath)) {
        echo "❌ Error: bootstrap/providers.php not found\n";
        return false;
    }

    $providers = file_get_contents($providersPath);
    
    // Check if already added
    if (strpos($providers, 'Webkul\\MercadoPago\\Providers\\MercadoPagoServiceProvider::class') !== false) {
        echo "ℹ️  Mercado Pago provider already exists\n";
        return true;
    }

    // Add provider after PayPal
    $search = "Webkul\\Paypal\\Providers\\PaypalServiceProvider::class,";
    $replace = $search . "\n    Webkul\\MercadoPago\\Providers\\MercadoPagoServiceProvider::class,";
    
    $updatedProviders = str_replace($search, $replace, $providers);
    file_put_contents($providersPath, $updatedProviders);
    
    echo "✅ Added Mercado Pago service provider\n";
    
    return true;
}

/**
 * Update system.php to add Mercado Pago configuration.
 */
function updateSystemConfig()
{
    $systemConfigPath = 'packages/Webkul/Admin/src/Config/system.php';
    
    if (!file_exists($systemConfigPath)) {
        echo "❌ Error: Admin system.php not found\n";
        return false;
    }

    $systemConfig = file_get_contents($systemConfigPath);
    
    // Check if already added
    if (strpos($systemConfig, 'sales.payment_methods.mercadopago') !== false) {
        echo "ℹ️  Mercado Pago configuration already exists\n";
        return true;
    }

    $mercadoPagoConfig = getMercadoPagoConfig();
    
    // Find insertion point (before order_settings)
    $insertPoint = "    ], [\n        'key'  => 'sales.order_settings',";
    $replacement = $mercadoPagoConfig . "\n    ], [\n        'key'  => 'sales.order_settings',";
    
    $updatedConfig = str_replace($insertPoint, $replacement, $systemConfig);
    file_put_contents($systemConfigPath, $updatedConfig);
    
    echo "✅ Added Mercado Pago admin configuration\n";
    
    return true;
}

/**
 * Update translation files.
 */
function updateTranslations()
{
    $translations = [
        'en' => getEnglishTranslations(),
        'pt_BR' => getPortugueseTranslations(),
    ];

    foreach ($translations as $locale => $content) {
        $translationPath = "packages/Webkul/Admin/src/Resources/lang/{$locale}/app.php";
        
        if (!file_exists($translationPath)) {
            echo "⚠️  Warning: Translation file not found: {$translationPath}\n";
            continue;
        }

        $currentTranslations = file_get_contents($translationPath);
        
        // Check if already added
        if (strpos($currentTranslations, 'mercadopago') !== false) {
            echo "ℹ️  Mercado Pago translations already exist for {$locale}\n";
            continue;
        }

        // Find insertion point
        $insertPoint = "'title'                          => 'Título',\n                ],\n            ],";
        $newTranslations = formatTranslations($content);
        $replacement = $newTranslations . "\n                ],\n            ],";
        
        $updatedTranslations = str_replace($insertPoint, $replacement, $currentTranslations);
        file_put_contents($translationPath, $updatedTranslations);
        
        echo "✅ Added {$locale} translations\n";
    }
    
    return true;
}

/**
 * Run post-installation commands.
 */
function runPostInstallCommands()
{
    echo "🔄 Clearing caches...\n";
    
    // Clear Laravel caches
    if (file_exists('artisan')) {
        shell_exec('php artisan config:clear 2>/dev/null');
        shell_exec('php artisan cache:clear 2>/dev/null');
        shell_exec('php artisan route:clear 2>/dev/null');
        echo "✅ Cleared Laravel caches\n";
    }
    
    return true;
}

/**
 * Get Mercado Pago configuration array.
 */
function getMercadoPagoConfig()
{
    return "    ], [
        'key'    => 'sales.payment_methods.mercadopago',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.mercadopago',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.mercadopago-info',
        'sort'   => 5,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.title',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'image',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.logo',
                'type'          => 'image',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.logo-information',
                'channel_based' => false,
                'locale_based'  => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name'          => 'public_key',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.mercadopago-public-key',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.mercadopago-public-key-info',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'access_token',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.mercadopago-access-token',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.mercadopago-access-token-info',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'webhook_url',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.mercadopago-webhook-url',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.mercadopago-webhook-url-info',
                'type'          => 'text',
                'depends'       => 'active:1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'webhook_secret',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.mercadopago-webhook-secret',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.mercadopago-webhook-secret-info',
                'type'          => 'text',
                'depends'       => 'active:1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'sandbox',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.sandbox',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.sort-order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ], [
                        'title' => '5',
                        'value' => 5,
                    ],
                ],
            ],
        ],";
}

/**
 * Get English translations.
 */
function getEnglishTranslations()
{
    return "'mercadopago'                     => 'Mercado Pago',
                    'mercadopago-info'                => 'Accept payments via Pix, Credit Card and Boleto through Mercado Pago - Latin America\'s leading payment platform',
                    'mercadopago-public-key'          => 'Mercado Pago Public Key',
                    'mercadopago-public-key-info'     => 'Your Mercado Pago public key (find at: mercadopago.com/developers > Credentials)',
                    'mercadopago-access-token'        => 'Mercado Pago Access Token',
                    'mercadopago-access-token-info'   => 'Your Mercado Pago access token (generate at: mercadopago.com/developers > Credentials)',
                    'mercadopago-webhook-url'         => 'Notification URL',
                    'mercadopago-webhook-url-info'    => 'URL to receive payment confirmations (e.g., https://yourstore.com/mercado-pago/webhook)',
                    'mercadopago-webhook-secret'      => 'Webhook Secret Key',
                    'mercadopago-webhook-secret-info' => 'Secret key to validate notifications (optional but recommended for security)',";
}

/**
 * Get Portuguese translations.
 */
function getPortugueseTranslations()
{
    return "'mercadopago'                     => 'Mercado Pago',
                    'mercadopago-info'                => 'Aceite pagamentos via Pix, Cartão de Crédito e Boleto através do Mercado Pago - a principal plataforma de pagamentos da América Latina',
                    'mercadopago-public-key'          => 'Chave Pública do Mercado Pago',
                    'mercadopago-public-key-info'     => 'Sua chave pública do Mercado Pago (encontre em: mercadopago.com.br/developers > Credenciais)',
                    'mercadopago-access-token'        => 'Token de Acesso do Mercado Pago',
                    'mercadopago-access-token-info'   => 'Seu token de acesso do Mercado Pago (gere em: mercadopago.com.br/developers > Credenciais)',
                    'mercadopago-webhook-url'         => 'URL para Notificações',
                    'mercadopago-webhook-url-info'    => 'URL para receber confirmações de pagamento (ex: https://sualoja.com/mercado-pago/webhook)',
                    'mercadopago-webhook-secret'      => 'Chave Secreta do Webhook',
                    'mercadopago-webhook-secret-info' => 'Chave secreta para validar notificações (opcional, mas recomendado para segurança)',";
}

/**
 * Format translations for insertion.
 */
function formatTranslations($content)
{
    return "                    'mercadopago'                     => 'Mercado Pago',
                    'mercadopago-info'                => 'Aceite pagamentos via Pix, Cartão de Crédito e Boleto através do Mercado Pago - a principal plataforma de pagamentos da América Latina',
                    'mercadopago-public-key'          => 'Chave Pública do Mercado Pago',
                    'mercadopago-public-key-info'     => 'Sua chave pública do Mercado Pago (encontre em: mercadopago.com.br/developers > Credenciais)',
                    'mercadopago-access-token'        => 'Token de Acesso do Mercado Pago',
                    'mercadopago-access-token-info'   => 'Seu token de acesso do Mercado Pago (gere em: mercadopago.com.br/developers > Credenciais)',
                    'mercadopago-webhook-url'         => 'URL para Notificações',
                    'mercadopago-webhook-url-info'    => 'URL para receber confirmações de pagamento (ex: https://sualoja.com/mercado-pago/webhook)',
                    'mercadopago-webhook-secret'      => 'Chave Secreta do Webhook',
                    'mercadopago-webhook-secret-info' => 'Chave secreta para validar notificações (opcional, mas recomendado para segurança)',";
}
