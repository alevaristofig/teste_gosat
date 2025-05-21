<?php

    namespace App\Service;

    use Illuminate\Support\Facades\Http;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use App\Models\OfertaCredito;

    class CreditoService {

        private $model;

        public function __construct(OfertaCredito $model) {
            $this->model = $model;
        }

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

        public function consultarMelhoresOfertas(string $cpf) {            
            $ofertas = $this->ofertarCredito($cpf);
            $ofertas = json_decode($ofertas);

            $aux_ofertas = [];
            $dados = [];
            $resultOfertas = [];


            foreach($ofertas as $value) {
                foreach($value as $valueDados) {
                    foreach($valueDados->modalidades as $modalidade) {                                              
                        $dados = [
                            'cpf' => $cpf,
                            'instituicao_id' =>$valueDados->id,
                            'codModalidade' => $modalidade->cod
                        ];

                        $resultAux = $this->consultarCredito($dados);
                      
                        $resultOfertas[] = [
                            'id' => $valueDados->id,
                            'nome' => $valueDados->nome,
                            'modalidadeCredito' => $modalidade->nome,
                            'ofertas' => $resultAux
                        ];
                    }
                }
            }

            $melhoresOfertas = [];
            $melhoresOfertasAux = [];
          
            foreach($resultOfertas as $key => $value) {
                $aux = json_decode($value['ofertas']);

                $auxOfertas1 = $this->calcularOfertas($aux->valorMin,$aux->jurosMes,$aux->QntParcelaMin);
                $auxOfertas2 = $this->calcularOfertas($aux->valorMax,$aux->jurosMes,$aux->QntParcelaMax);

                 $dadosOferta = ($auxOfertas1['totalComJuros'] <  $auxOfertas2['totalComJuros']) 
                    ? 
                        $auxOfertas1
                    :  
                        $auxOfertas2;

                $melhoresOfertas[$key] = [
                    'id' => $value['id'],
                    'instituicaoFinanceira' => $value['nome'],
                    'modalidadeCredito' => $value['modalidadeCredito'],
                    'oferta' => [
                        'valorAPagar' => $dadosOferta['totalComJuros'],
                        'valorSolicitado' => ($auxOfertas1['totalComJuros'] <  $auxOfertas2['totalComJuros']) 
                            ? 
                                $aux->valorMin
                            :  
                                $aux->QntParcelaMax,
                        'taxaJuros' => $dadosOferta['juros'],
                        'qntParcelas' => $aux->QntParcelaMin
                    ]                    
                ];
            }

          usort($melhoresOfertas, function ($v1, $v2) {
            return $v1['oferta']['valorAPagar'] <=> $v2['oferta']['valorAPagar'];
          });

          return $melhoresOfertas;
        }

        public function salvarOferta(Request $request) {

        }

        private function calcularOfertas(float $valor, float $juros, float $qntParcela): array {
             $juros = $valor * $juros * $qntParcela;
             $totalComJuros = ($valor * $qntParcela) + $juros;

             return ['juros' => $juros, 'totalComJuros' => $totalComJuros];
        }
    }