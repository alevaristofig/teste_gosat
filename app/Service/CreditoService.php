<?php

    namespace App\Service;

    use Illuminate\Support\Facades\Http;
    use Illuminate\Http\JsonResponse;

    class CreditoService {

        public function ofertarCredito(string $cpf): string {

            $response = Http::withOptions([
                'verify' => false,
            ])->post('https://dev.gosat.org/api/v1/simulacao/credito',[
                    'cpf' => $cpf,
                ]
            );

            return $response->body();
        }

        public function consultarCredito(array $dados): string {

            $response = Http::withOptions([
                'verify' => false,
            ])->post('https://dev.gosat.org/api/v1/simulacao/oferta',[
                    'cpf' => $dados['cpf'],
                    'instituicao_id' => $dados['instituicao_id'],
                    'codModalidade' => $dados['codModalidade']
                ]
            );

            return $response->body();
        }
    }