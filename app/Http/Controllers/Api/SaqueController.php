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
use App\Models\SolicitacoesCashOut;

class SaqueController extends Controller
{
    //use CashtimeTrait;
    use InterTrait, PradaPayTrait;

    public function makePayment(Request $request)
    {
        // Rate limit por usuário
        $this->rateLimitCheck($request->user->id ?? null);
        
        Helper::calculaSaldoLiquido($request->user->user_id);
        $setting = App::first();

        $user = User::where('id', $request->user->id)->first();

        if ((float) $user->saldo < (float)$request->amount) {
            return response()->json(['status' => 'error', 'message' => 'Saldo Insuficiente.'], 401);
        }

        try {
            $validated = $request->validate([
                'token'             => ['required', 'string'],
                'secret'            => ['required', 'string'],
                'amount'            => ['required'],
                'pixKey'            => ['required', 'string'],
                'pixKeyType'        => ['required', 'string', 'in:cpf,email,telefone,aleatoria,cnpj,random'],
                'baasPostbackUrl'   => ['required', 'string'],
                'nonce'             => ['required', 'string']
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

        $existNonce = SolicitacoesCashOut::where('nonce', $request->nonce)->first();
        if($existNonce) {
            return response()->json(['status' => 'error', 'message' => 'Requisição já processada.'], 401);
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

    /**
     * Verifica e aplica rate limit por usuário (máximo 3 requisições por segundo).
     * Lança uma exceção HTTP 429 se o limite for excedido.
     */
    private function rateLimitCheck($userId)
    {
        $rateLimitDir = storage_path('app/rate_limit');
        if (!is_dir($rateLimitDir)) {
            mkdir($rateLimitDir, 0777, true);
        }
        
        $userKey = $userId ?? 'unknown';
        $userKeyHash = md5($userKey);
        $rateFile = $rateLimitDir . '/' . $userKeyHash . '.json';
        $now = microtime(true);
        $blockFile = $rateLimitDir . '/' . $userKeyHash . '.block';
        
        // Se estiver bloqueado, retorna erro
        if (file_exists($blockFile)) {
            $blockTime = (int)file_get_contents($blockFile);
            if ($blockTime > time()) {
                abort(429, 'Rate limit excedido. Tente novamente em 1 minuto.');
            } else {
                unlink($blockFile);
            }
        }
        
        // Lê histórico de requisições
        $history = [];
        if (file_exists($rateFile)) {
            $history = json_decode(file_get_contents($rateFile), true);
            if (!is_array($history)) $history = [];
        }
        
        // Remove requisições mais antigas que 1 segundo
        $history = array_filter($history, function($ts) use ($now) {
            return ($now - $ts) < 1.0;
        });
        
        // Adiciona timestamp atual
        $history[] = $now;
        
        // Se excedeu 3 requisições no último segundo, bloqueia por 1 minuto
        if (count($history) > 3) {
            file_put_contents($blockFile, time() + 60);
            abort(429, 'Rate limit excedido. Tente novamente em 1 minuto.');
        }
        
        // Salva histórico atualizado
        file_put_contents($rateFile, json_encode($history));
    }
}
