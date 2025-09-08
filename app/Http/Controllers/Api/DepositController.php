<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CashtimeTrait;
use App\Traits\JupiterPayTrait;
use App\Traits\PradaPayTrait;
use App\Traits\InterTrait;
use App\Models\Solicitacoes;
use App\Models\App;

/**
 * @OA\Info(
 *     title="API Rest PIX",
 *     version="1.0.0",
 *     description="Documentação"
 * )
 */

class DepositController extends Controller
{
    //use CashtimeTrait;
    use JupiterPayTrait, InterTrait, PradaPayTrait;

    public function makeDeposit(Request $request)
    {
        $setting = App::first();

        if ($setting->deposito_minimo > 0 && $request->amount < $setting->deposito_minimo) {
            $valorret = number_format($setting->deposito_minimo, '2', ',', '.');
            return response()->json([
                'status' => 'error',
                'message' => "O valor mínimo de depósito é de R$ $valorret."
            ], 401);
        }
        try {
            $validated = $request->validate([
                'token' => ['required', 'string'],
                'secret' => ['required', 'string'],
                'amount' => ['required'],
                'debtor_name' => ['required', 'string'],
                'email' => ['required', 'string', 'email'],
                'debtor_document_number' => ['required', 'string'],
                'phone' => ['required', 'string'],
                'method_pay' => ['required', 'string'],
                'postback' => ['required', 'string'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422); // Status code 422 para erros de validação
        }

        $user = $this->getUserByToken($request->token, $request->secret);
        if(empty($user->gateway_cashin) || $user->gateway_cashin == null) {
            $user->gateway_cashin = $setting->gateway_cashin_default;
        }

        switch($user->gateway_cashin) {
            case 'pradapay':
                $response = self::requestDepositPradaPay($request);
                break;
            case 'jupiterpay':
                $response = self::requestDepositJupiterpay($request);
                break;
            case 'inter':
                $response = self::requestDepositInter($request);
                break;
            default:
                $response = self::requestDepositInter($request);
                break;
        }

        // Se passar pela validação, processar o depósito
        if(isset($response['data']) && isset($response['status'])) {
            return response()->json($response['data'], $response['status']);
        }

        return response()->json(['status' => 'error', 'message' => 'Erro ao processar depósito.'], 400);
    }

    public function statusDeposito(Request $request)
    {
        $deposit = Solicitacoes::where('idTransaction', $request->idTransaction)->first();
        return response()->json(['status' => $deposit->status]);
    }

    public function getUserByToken($token, $secret)
    {
        $user = \App\Models\UsersKey::where('users_key.token', $token)
            ->where('users_key.secret', $secret)
            ->join('users', 'users.user_id', '=', 'users_key.user_id')
            ->select('users.*')
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuário não encontrado ou token inválido.'
            ], 401);
        }

        return $user;
    }
}
