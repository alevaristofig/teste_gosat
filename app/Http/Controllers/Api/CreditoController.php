<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\CreditoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CreditoController extends Controller
{
    private $service;

    public function __construct(CreditoService $service) {
        $this->service = $service;
    }

    public function ofertarCredito(Request $request): JsonResponse {
        
        $result = $this->service->ofertarCredito($request->all()['cpf']);

        return response()->json(json_decode($result),200);
    }
}
