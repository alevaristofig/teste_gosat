<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service) {
        $this->service = $service;
    }

    public function login(Request $request): JsonResponse {
        $result = $this->service->login($request);

        return response()->json($result,200);
    }

    public function logout() {
        $result = $this->service->logout();

        return response()->json($result,200);
    }
}
