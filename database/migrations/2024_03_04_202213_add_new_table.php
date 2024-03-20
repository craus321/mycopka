<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mongodb')->create('table_cross', function (Blueprint $collection) {
            $collection->increments('_id');
            $collection->json('params');
            $collection->integer('counter')->default(0);
            $collection->timestamps(); // Добавляет поля created_at и updated_at типа timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('table_cross');

    }
};
