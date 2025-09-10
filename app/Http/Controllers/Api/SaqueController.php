<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\PixKeyType;
use App\Traits\CashtimeTrait;
use App\Traits\PradaPayTrait;
use App\Traits\InterTrait;
use App\Models\User;
use App\Models\App;
use App\Helpers\Helper;

class SaqueController extends Controller
{
    //use CashtimeTrait;
    use InterTrait, PradaPayTrait;

    public function makePayment(Request $request)
    {
        Helper::calculaSaldoLiquido($request->user->user_id);
        $setting = App::first();

        $user = User::where('id', $request->user->id)->first();

        if ((float) $user->saldo < (float)$request->amount) {
            return response()->json(['status' => 'error', 'message' => 'Saldo Insuficiente.'], 401);
        }

        try {
            $validated = $request->validate([
                'token' =>    ['required', 'string'],
                'secret' =>    ['required', 'string'],
                'amount' =>    ['required'],
                'pixKey' => ['required', 'string'],
                'pixKeyType' =>    ['required', 'string', 'in:cpf,email,telefone,aleatoria,cnpj,random'],
                'baasPostbackUrl' =>    ['required', 'string']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422); // Status code 422 para erros de validação
        }

        if ($request->amount < $setting->saque_minimo) {
            $saqueminimo = "R$ " . number_format($setting->saque_minimo, '2', ',', '.');
            return response()->json([
                'status' => 'error',
                'message' => "O saque mínimo é de $saqueminimo.",
            ], 401);
        }

        switch ($user->gateway_cashout) {
            case 'pradapay':
                $response = self::requestPaymentPradaPay($request);
                break;
            case 'inter':
                $response = self::requestPaymentInter($request);
                break;
            default:
                $response = self::requestPaymentInter($request);
                break;
        }

        if(!isset($response["data"])) {
            return response()->json(['status' => 'error', 'message' => 'Erro ao processar pagamento.'], 401);
        }

        return response()->json($response['data'], $response['status']);
    }
}
