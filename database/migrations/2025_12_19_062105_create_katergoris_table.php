<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('kode_kategori');
            $table->timestamps();
        });

        Schema::create('kategori_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_item');
            $table->unsignedBigInteger('id_kategori');

            $table->foreign('id_item')->references('id')->on('master_items')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id')->on('kategoris')->onDelete('cascade');

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
        Schema::dropIfExists('katergoris');
    }
};
