<?php

    namespace App\Service;

    use Illuminate\Support\Facades\Http;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use App\Models\OfertaCredito;
    use App\Exceptions\ApiMessages;

    class CreditoService {

        private $model;

        public function __construct(OfertaCredito $model) {
            $this->model = $model;
        }

        public function ofertarCredito(string $cpf, string $tipo): JsonResponse | string {

            try {
                $response = Http::withOptions([
                    'verify' => false,
                ])->post('https://dev.gosat.org/api/v1/simulacao/credito',[
                        'cpf' => $cpf,
                    ]
                );

                return $tipo === 'I' ? $response->body() : response()->json(json_decode($response->body()),200);
            } catch(\Exception $e) {
                $message = new ApiMessages($e->getMessage());
                return response()->json(['error' => $message->getMessage()], 401);
            }           
        }

        public function consultarCredito(array $dados, string $tipo): JsonResponse | string {

            try {               
                $response = Http::withOptions([
                    'verify' => false,
                ])->post('https://dev.gosat.org/api/v1/simulacao/oferta',[
                        'cpf' => $dados['cpf'],
                        'instituicao_id' => $dados['instituicao_id'],
                        'codModalidade' => $dados['codModalidade']
                    ]
                );

                return $tipo === 'I' ? $response->body() : response()->json(json_decode($response->body()),200);
            } catch(\Exception $e) {
                $message = new ApiMessages($e->getMessage());
                return response()->json(['error' => $message->getMessage()], 401);
            }              
        }

        public function consultarMelhoresOfertas(string $cpf) {            
            $ofertas = $this->ofertarCredito($cpf,"I");
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

                        $resultAux = $this->consultarCredito($dados,'I');
                      
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
                    'cpf' => $cpf,
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

        public function salvarOferta(Request $request): JsonResponse {
            try {                                
                $result = $this->model->create($request->all());  
                
                return response()->json($result,200);
            } catch(\Exception $e) {
                $message = new ApiMessages($e->getMessage());
                return response()->json(['error' => $message->getMessage()], 401);
            }
        }

        public function calcularOferta(Request $dados) {   
            try {
                $ofertasAux = $this->consultarCredito($dados->all(),'I');
                $ofertasAux = json_decode($ofertasAux);

                $ofertas1 = $this->calcularOfertas($ofertasAux->valorMin,$ofertasAux->jurosMes,$ofertasAux->QntParcelaMin);                
                $ofertas2 = $this->calcularOfertas($ofertasAux->valorMax,$ofertasAux->jurosMes,$ofertasAux->QntParcelaMax);

                $ofertas = [
                    'instituicao_id' => $dados->all()['instituicao_id'],
                    'codModalidade' =>  $dados->all()['codModalidade'],
                    'oferta1' => [
                        'idOferta' => 1,
                        'valorAPagar' => $ofertas1['totalComJuros'],
                        'valorSolicitado' => $ofertasAux->valorMax,
                        'taxaJuros' => $ofertas1['juros'],
                        'qntParcelas' => $ofertasAux->QntParcelaMax
                    ],
                    'oferta2' => [
                        'idOferta' => 2,
                        'valorAPagar' => $ofertas2['totalComJuros'],
                        'valorSolicitado' => $ofertasAux->valorMin,
                        'taxaJuros' => $ofertas2['juros'],
                        'qntParcelas' => $ofertasAux->QntParcelaMin
                    ]                
                ];

                return response()->json([$ofertas],200);
            }  catch(\Exception $e) {
                $message = new ApiMessages($e->getMessage());
                return response()->json(['error' => $message->getMessage()], 401);
            }        
        }

        private function calcularOfertas(float $valor, float $juros, float $qntParcela): array {
             $juros = $valor * $juros * $qntParcela;
             $totalComJuros = ($valor * $qntParcela) + $juros;

             return ['juros' => $juros, 'totalComJuros' => $totalComJuros];
        }
    }