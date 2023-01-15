<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //Login
    public function login(Request $request)
    {
         //Autenticação (email e senha)
         $credenciais = $request->only(['email', 'password']);
         $token = auth('api')->attempt($credenciais);

         if($token){ //Usuario autenticado com sucesso

             return response()->json(['token' => $token], 200);

         } else { //Erro de usuario ou senha

            return response()->json(['error' => 'Usuário ou senha inválidos'], 403);
         }
    }

    //Logout
    public function logout(){
        //O metodo logout é responsavel para invalidar um JWT
        auth('api')->logout();
        return response()->json(['msg' => 'Logout realizado com sucesso'], 200);
    }
    //refresh
    public function refresh()
    {
        $token = auth('api')->refresh(); //cliente encaminha um jwt valido
        return response()->json(['token' => $token], 200);
    }
    //me
    public function me(){
        $me = response()->json(auth('api')->user());
        return $me;
    }
}
