<?php

    namespace App\Repository;

    use Illuminate\Http\Request;
    use Illuminate\Http\JsonResponse;

    interface UserRepository {
        
        public function login(Request $request): JsonResponse;
    }