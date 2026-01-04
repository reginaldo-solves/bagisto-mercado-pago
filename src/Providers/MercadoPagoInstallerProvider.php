<?php

namespace Webkul\MercadoPago\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class MercadoPagoInstallerProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->publishConfiguration();
        $this->publishTranslations();
    }

    /**
     * Register services.
     */
    public function register()
    {
        // Registrar automaticamente as configurações
    }

    /**
     * Publish configuration to Bagisto Admin.
     */
    protected function publishConfiguration()
    {
        $systemConfigPath = base_path('packages/Webkul/Admin/src/Config/system.php');
        
        if (!File::exists($systemConfigPath)) {
            return;
        }

        $systemConfig = File::get($systemConfigPath);
        
        // Verificar se já foi adicionado
        if (strpos($systemConfig, 'sales.payment_methods.mercadopago') !== false) {
            return;
        }

        // Adicionar configuração do Mercado Pago
        $mercadoPagoConfig = $this->getMercadoPagoConfig();
        
        // Encontrar o local correto para inserir
        $insertPoint = "], [\n        'key'  => 'sales.order_settings',";
        $replacement = $mercadoPagoConfig . "\n    ], [\n        'key'  => 'sales.order_settings',";
        
        $updatedConfig = str_replace($insertPoint, $replacement, $systemConfig);
        File::put($systemConfigPath, $updatedConfig);
    }

    /**
     * Publish translations to Bagisto Admin.
     */
    protected function publishTranslations()
    {
        $translations = [
            'en' => $this->getEnglishTranslations(),
            'pt_BR' => $this->getPortugueseTranslations(),
        ];

        foreach ($translations as $locale => $content) {
            $translationPath = base_path("packages/Webkul/Admin/src/Resources/lang/{$locale}/app.php");
            
            if (File::exists($translationPath)) {
                $currentTranslations = File::get($translationPath);
                
                // Verificar se já foi adicionado
                if (strpos($currentTranslations, 'mercadopago') !== false) {
                    continue;
                }

                // Adicionar traduções do Mercado Pago
                $insertPoint = "'title'                          => 'Título',\n                ],\n            ],";
                $newTranslations = $this->formatTranslations($content);
                $replacement = $newTranslations . "\n                ],\n            ],";
                
                $updatedTranslations = str_replace($insertPoint, $replacement, $currentTranslations);
                File::put($translationPath, $updatedTranslations);
            }
        }
    }

    /**
     * Get Mercado Pago configuration.
     */
    protected function getMercadoPagoConfig()
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
    protected function getEnglishTranslations()
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
    protected function getPortugueseTranslations()
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
    protected function formatTranslations($content)
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
}
