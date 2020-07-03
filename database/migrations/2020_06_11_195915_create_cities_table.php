<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('city_name');
            $table->string('city_alt_name')->nullable();
            // kode kota berdasarkan data dagri
            $table->string('city_code',8)->nullable();
            // kode kota berdasarkan data instansi - ex : dikbud, bps dll
            $table->string('city_alt_code',8)->nullable();
            // jenis : [kota, kabupaten]
            $table->string('city_type',5)->nullable();
            // relasi ke table propinsi
            $table->unsignedBigInteger('province_id')->nullable();
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');

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
        Schema::dropIfExists('cities');
    }
}
