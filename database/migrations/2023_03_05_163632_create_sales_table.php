<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->date('sale_date');
            $table->decimal('subtotal', 10)->default(0);
            $table->decimal('tax', 10)->default(0);
            $table->string('tax_value_type', 10)->default('percent')->comment('percent/fixed');
            $table->decimal('discount', 10)->default(0);
            $table->string('discount_value_type', 10)->default('percent')->comment('percent/fixed');
            $table->decimal('total', 10)->default(0);
            $table->text('note')->nullable();
            $table->foreignId('gateway_id')->nullable()->constrained('gateways');
            $table->string('status')->default('sold')->comment('hold/sold');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
