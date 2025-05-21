<?php

    namespace App\Service;

    use App\Repository\UserRepository;
    use App\Models\User;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Ramsey\Uuid\Uuid;

    class UserService implements UserRepository {

        private $model;

        public function __construct(User $model) {
            $this->model = $model;
        }

        public function Login(Request $request): JsonResponse {
            $data = $request->validate([
                'email' => 'required | string | email',
                'password' => 'required'
            ]);

            $usuario = $this->model->where('email', $data)->first();

            $data['password'] = bcrypt($data['password']);    

            if(!$usuario || Auth::attempt($data))
            {            
                return response()->json(['error' => "Acesso Negado"], 401); 
            }

            $token = $usuario->createToken(Uuid::uuid4())->plainTextToken;

            return response()->json([
                'token' => $token,
                'cpf' => $usuario->cpf
            ]);
        }

        public function logout() {
            $this->model->tokens()->delete();

            return response()->json([
                "message"=>"logged out"
            ]);
        }
    }