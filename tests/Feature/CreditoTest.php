<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreditoTest extends TestCase
{
    public function test_BuscarOfertaCredidoSucesso(): void
    {
        $response = $this->post('api/v1/simulacao/ofertaCredito',['cpf' => '11111111111']);

        $this->assertEquals('Banco PingApp',$response['instituicoes'][0]['nome']);
    }
}
