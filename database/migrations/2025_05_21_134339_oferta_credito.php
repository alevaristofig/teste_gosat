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
        Schema::create('oferta_credito', function (Blueprint $table) {
            $table->bigIncrements('id');            

            $table->integer('id_instiuicao');
            $table->string('cpf');
            $table->string('instituicao_financeira');
            $table->string('modalidade_credito');
            $table->float('valor_a_pagar',10,2);
            $table->float('valor_solicitado',10,2);
            $table->float('taxa_juros',10,2);
            $table->integer('qnt_parcelas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta_credito');
    }
};
