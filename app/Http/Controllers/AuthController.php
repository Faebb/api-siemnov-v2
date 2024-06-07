<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse as Response;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Validator; // Añade esta línea


class AuthController extends Controller
{
    public function FirstRegister(Request $request) {
        if (User::exists()) {
            return Response::error('Lo siento acción no valida ya existe un usuario admin', null, 401);
        }else {
            $validated = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);

            if ($validated->fails()) {
                return ApiResponse::error('Error de validación', $validated->errors(), 422);
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $data['token'] = $user->createToken('my-app-token')->plainTextToken;
            $data['name'] = $user->name;

            return ApiResponse::Success('Primer usuario creado correctamente', $data, 201);
        }
    }
}
