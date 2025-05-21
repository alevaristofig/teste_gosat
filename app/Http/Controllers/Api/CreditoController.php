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
        
        $result = $this->service->ofertarCredito($request->all()['cpf']);

        return response()->json(json_decode($result),200);
    }

    public function simularCredito(Request $request): JsonResponse {        
        $result = $this->service->consultarCredito($request->all());

        return response()->json(json_decode($result),200);
    }

    public function consultarMelhoresOfertas(Request $request) {
        $result = $this->service->consultarMelhoresOfertas($request->all()['cpf']);

        return response()->json($result,200);
    }
}
