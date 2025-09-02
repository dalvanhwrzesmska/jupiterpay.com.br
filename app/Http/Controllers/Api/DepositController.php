<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CashtimeTrait;
use App\Traits\JupiterPayTrait;
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
    use CashtimeTrait;
    use JupiterPayTrait;

    /**
     * @OA\Post(
     *     path="/wallet/deposit/payment",
     *     tags={"cash-in"},
     *     summary="Gerar QrCode",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             schema=@OA\Schema(
     *                 type="object",
     *                 required={
     *                     "token", "secret", "amount", "debtor_name",
     *                     "email", "debtor_document_number", "phone",
     *                     "method_pay", "postback"
     *                 },
     *                 @OA\Property(property="token", type="string", example="abc123token"),
     *                 @OA\Property(property="secret", type="string", example="mySecretKey"),
     *                 @OA\Property(property="amount", type="number", format="float", example=100.50),
     *                 @OA\Property(property="debtor_name", type="string", example="João Silva"),
     *                 @OA\Property(property="email", type="string", format="email", example="joao@email.com"),
     *                 @OA\Property(property="debtor_document_number", type="string", example="12345678900"),
     *                 @OA\Property(property="phone", type="string", example="+5511999999999"),
     *                 @OA\Property(property="method_pay", type="string", example="pix"),
     *                 @OA\Property(property="postback", type="string", example="https://minhaapi.com/callback")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna os dados do QrCode",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="idTransaction", type="string", example="TX123"),
     *             @OA\Property(property="qrcode", type="string", example="código copia e cola"),
     *             @OA\Property(property="qr_code_image_url", type="string", example="https://exemplo.com/qrcode.png")
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string", default="application/json")
     *     ),
     *     @OA\Parameter(
     *         name="Content-Type",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string", default="application/json")
     *     )
     * )
     */


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
            case 'cashtime':
                $response = self::requestDepositCashtime($request);
                break;
            case 'jupiterpay':
                $response = self::requestDepositJupiterpay($request);
                break;
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gateway de depósito não suportado.'
                ], 400);
        }

        // Se passar pela validação, processar o depósito
        return response()->json($response['data'], $response['status']);
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
