<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('name');
            $table->index('category_id');
            $table->index('price');
            $table->index('in_stock');
            $table->index('rating');
            $table->index('created_at');
        });

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('products', function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['price']);
            $table->dropIndex(['in_stock']);
            $table->dropIndex(['rating']);
            $table->dropIndex(['created_at']);
        });
    }
};
