<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->integer('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('departments');
            
            $table->string('item_code')->unique();
            $table->string('item_name');
            $table->string('unit')->nullable();
            
            
            $table->integer('first_stock')->default(0);
            $table->integer('in_stock')->default(0);
            $table->integer('out_stock')->default(0);
            $table->integer('now_stock')->default(0);
            
            $table->string('barcode')->default("0000");
            $table->string('details')->default('')->nullable();
            $table->boolean('deleted')->default(false);
            
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
        Schema::dropIfExists('items');
    }
}
