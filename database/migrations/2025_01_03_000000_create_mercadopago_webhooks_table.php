<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMercadopagoWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercadopago_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->nullable();
            $table->string('external_reference')->nullable();
            $table->string('status');
            $table->string('status_detail')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->decimal('transaction_amount', 12, 2);
            $table->decimal('transaction_amount_refunded', 12, 2)->default(0);
            $table->integer('installments')->default(1);
            $table->string('description')->nullable();
            $table->json('payer')->nullable();
            $table->json('metadata')->nullable();
            $table->json('additional_info')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();

            $table->index('payment_id');
            $table->index('external_reference');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mercadopago_webhooks');
    }
}
