<?php

    namespace App\Repository;

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
    }