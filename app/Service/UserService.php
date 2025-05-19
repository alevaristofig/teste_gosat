<?php

    namespace App\Service;

    use App\Repository\UserRepository;
    use Illuminate\Http\JsonResponse;
    use Ramsey\Uuid\Uuid;

    class UserService {

        private $repository;

        public function __construct(UserRepository $repository) {
            $this->repository = $repository;
        }

        public function Login(Request $request) {
            $data = $request->validate([
                'email' => 'required | string | email',
                'password' => 'required'
            ]);

            $usuario = $this->repository->where('email', $data)->first();

            if(!$usuario || !Hash::check($data['password'],$usuario->password)){
                return response()->json([
                    'message' => 'Email/password inválidos'
                ],401);
            }

            $token = $usuario->createToken(Uuid::uuid4())->plainTextToken;

            return response()->json([
                'token' => $token,
            ]);

        }
    }