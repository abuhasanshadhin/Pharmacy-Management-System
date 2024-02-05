<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->nullable();
            $table->string('sku')->unique();
            $table->string('name');
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->string('generic_name');
            $table->string('strength')->nullable();
            $table->string('image')->nullable();
            $table->decimal('purchase_price')->default(0);
            $table->decimal('sale_price')->default(0);
            $table->decimal('tax')->default(0);
            $table->string('tax_value_type', 10)->nullable()->comment('percent/fixed');
            $table->string('status')->default('active')->comment('active/inactive');
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
        Schema::dropIfExists('products');
    }
}
