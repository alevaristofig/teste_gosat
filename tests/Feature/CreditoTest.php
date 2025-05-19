<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreditoTest extends TestCase
{
    public function test_BuscarOfertaCredidoSucesso(): void
    {
        $response = $this->post('api/v1/simulacao/consultacredito',['cpf' => '11111111111']);

        $this->assertEquals('Banco PingApp',$response['instituicoes'][0]['nome']);
    }

    public function test_SimularOfertaCredidoSucesso(): void
    {
        $response = $this->post('api/v1/simulacao/simulacredito',[
            'cpf' => '11111111111',
            'instituicao_id' => 2,
            'codModalidade' => 'a50ed2ed-2b8b-4cc7-ac95-71a5568b34ce'
        ]);

        $this->assertEquals(12,$response['QntParcelaMin']);
    }
}
