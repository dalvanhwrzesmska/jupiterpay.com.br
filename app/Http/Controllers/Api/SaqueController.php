<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\PixKeyType;
use App\Traits\CashtimeTrait;
use App\Models\User;
use App\Models\App;
use App\Helpers\Helper;

class SaqueController extends Controller
{
    use CashtimeTrait;

    /**
     * @OA\Post(
     *     path="/pixout",
     *     tags={"pix-out"},
     *     summary="Solicitar Saque via Pix",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             schema=@OA\Schema(
     *                 type="object",
     *                 required={
     *                     "token", "secret", "amount", "pixKey",
     *                     "pixKeyType", "baasPostbackUrl"
     *                 },
     *                 @OA\Property(property="token", type="string", example="abc123token"),
     *                 @OA\Property(property="secret", type="string", example="mySecretKey"),
     *                 @OA\Property(property="amount", type="number", format="float", example=100.00),
     *                 @OA\Property(property="pixKey", type="string", example="chave_pix@exemplo.com"),
     *                 @OA\Property(property="pixKeyType", type="string", enum={"cpf", "email", "telefone", "aleatoria"}, example="cpf"),
     *                 @OA\Property(property="baasPostbackUrl", type="string", example="https://minhaapi.com/postback")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna dados do saque Pix",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", format="uuid", example="b522a295-e404-4f7c-a2cb-aaaabbbbcccc"),
     *             @OA\Property(property="amount", type="number", example=100),
     *             @OA\Property(property="pixKey", type="string", example="chave"),
     *             @OA\Property(property="pixKeyType", type="string", example="cpf"),
     *             @OA\Property(property="withdrawStatusId", type="string", example="PendingProcessing"),
     *             @OA\Property(property="createdAt", type="string", format="date-time", example="2025-04-19T20:04:53.166Z"),
     *             @OA\Property(property="updatedAt", type="string", format="date-time", example="2025-04-19T20:04:53.166Z")
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
                'pixKeyType' =>    ['required', 'string', 'in:cpf,email,telefone,aleatoria'],
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

        $response = self::requestPaymentCashtime($request);

        // Se passar pela validação, processar o depósito
        return response()->json($response['data'], $response['status']);
    }
}
