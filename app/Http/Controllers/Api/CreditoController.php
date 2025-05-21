<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\CreditoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CreditoController extends Controller
{
    private $service;

    public function __construct(CreditoService $service) {
        $this->service = $service;
    }

    public function consultacredito(Request $request): JsonResponse {        
        return $this->service->ofertarCredito($request->all()['cpf'],'C');       
    }

    public function simularCredito(Request $request): JsonResponse {        
        return $this->service->consultarCredito($request->all());        
    }

    public function consultarMelhoresOfertas(Request $request) {
        $result = $this->service->consultarMelhoresOfertas($request->all()['cpf']);

        return response()->json($result,200);
    }

    public function salvarOferta(Request $request) {
       return $this->service->salvarOferta($request);
    }
}
