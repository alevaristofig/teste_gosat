<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfertaCredito extends Model
{
    protected $table = "oferta_credito";

    protected $fillable = [
        'id_instiuicao', 'cpf', 'instituicao_financeira', 'modalidade_credito', 'valor_a_pagar',
        'valor_solicitado', 'taxa_juros', 'qnt_parcelas'
    ];
}
