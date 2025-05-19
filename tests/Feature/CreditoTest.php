<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreditoTest extends TestCase
{
    public function test_BuscarOfertaCredidoSucesso(): void
    {
        $response = $this->post('/v1/simulacao/credito',['cpf' => '11111111111']);

        $response->assertStatus(200);
    }
}
