<?php

namespace App\Traits;

use Brick\Math\Exception\DivisionByZeroException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\App;
use App\Models\User;
use App\Models\UsersKey;
use App\Models\Cashtime;
use Faker\Factory as FakerFactory;
use App\Helpers\Helper;
use App\Models\TokenInter;

trait InterTrait
{
    protected static string $clientIdInter;
    protected static string $clienteSecretInter;
    protected static string $urlInter;
    protected static string $taxaCashIn;
    protected static string $taxaCashOut;
    protected static string $certPath;
    protected static string $keyPath;

    protected static function generateCredentialsInter()
    {

        $setting = Cashtime::first();
        if (!$setting) {
            return false;
        }

        $sandbox = false;

        self::$clientIdInter = 'ca30daa2-94eb-4ce2-8a64-7f26d87437cb';
        self::$clienteSecretInter = '55590fc7-6ccd-4572-9548-77fcc373dfed';

        self::$certPath = storage_path('cert/inter/inter.crt');
        self::$keyPath = storage_path('cert/inter/inter.key');

        if($sandbox){
            self::$urlInter = 'https://cdpj-sandbox.partners.uatinter.co';
        }else{
            self::$urlInter = 'https://cdpj.partners.bancointer.com.br';
        }

        self::$taxaCashIn = $setting->taxa_pix_cash_in;
        self::$taxaCashOut = $setting->taxa_pix_cash_out;

        return true;
    }

    public static function createTaxBalanceInter($payload, $taxa)
    {
        $payload['idTransaction'] = $payload['idTransaction'] . '_TAX';
        $payload['externalreference'] = $payload['externalreference'] . '_TAX';
        $payload['type'] = 'TAX';
        $payload['amount'] = $taxa;
        $payload['beneficiaryname'] = 'Taxa de Saque';
        $payload['cash_out_liquido'] = $taxa;
        $payload['taxa_cash_out'] = $taxa;
        $payload['amount'] = 0;
        $payload['pix'] = '';
        $payload['pixkey'] = 'tax';
        SolicitacoesCashOut::create($payload);
    }

    public static function authTokenInter()
    {
        if (self::generateCredentialsInter()) {

            $existingToken = TokenInter::latest()->first();
            if ($existingToken) {
                $expiresAt = Carbon::parse($existingToken->expires_at);
                if ($expiresAt->isFuture()) {
                    return $existingToken->access_token;
                }
            }

            $query = [
                'client_id' => self::$clientIdInter,
                'client_secret' => self::$clienteSecretInter,
                'scope' => 'extrato.read cob.write cob.read pix.read pix.write webhook.read webhook.write pagamento-pix.write pagamento-pix.read',
                'grant_type' => 'client_credentials'
            ];

            $response = Http::asForm()
                ->withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ])
                ->withOptions([
                    'cert' => self::$certPath,
                    'ssl_key' => self::$keyPath,
                ])
                ->post(self::$urlInter . '/oauth/v2/token', $query);

            if ($response->successful()) {
                $responseData = $response->json();
                TokenInter::create([
                    'access_token' => $responseData['access_token'],
                    'expires_at' => Carbon::now()->addSeconds($responseData['expires_in'])
                ]);
                return $responseData['access_token'];
            } else {
                Log::error('Erro ao obter token de autenticação Inter: ' . $response->body());
                return null;
            }
        } else {
            return null;
        }
    }

    public static function requestDepositInter($data)
    {
        if (self::generateCredentialsInter()) {
            $client_ip = $data->ip();
            $document = Helper::generateValidCpf();

            $payload = [
                "calendario" => [
                    "expiracao" => 3600
                ],
                "devedor" => [
                    "cpf" => $document,
                    "nome" => $data->debtor_name
                ],
                "valor" => [
                    "original" => number_format($data->amount, 2, '.', ''),
                    "modalidadeAlteracao" => 1
                ],
                "chave" => "ccacf2ee-558c-4ba3-a137-d9f6e582db5a"
                /*"solicitacaoPagador" => "Serviço realizado.",
                "infoAdicionais" => [
                    [
                        "nome" => "Campo 1",
                        "valor" => "Informação Adicional1 do PSP-Recebedor"
                    ]
                ]*/
            ];

            $bearerToken = self::authTokenInter();

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $bearerToken
            ])->withOptions([
                'cert' => self::$certPath,
                'ssl_key' => self::$keyPath,
            ])->post(self::$urlInter . '/pix/v2/cob', $payload);

            //dd($bearerToken, $response->json());

            if ($response->successful()) {

                $responseData = $response->json();
                $user = $data->user;

                $app = App::first();
                $taxa_cash_in = $app->taxa_cash_in ?? 2;
                $taxa_cash_out = $app->taxa_cash_out ?? 2;
                $taxa_fixa_padrao = $app->taxa_fixa_padrao;

                $userKey = UsersKey::where(['token' => $data->token, 'secret' => $data->secret])->first();
                $userData = User::where('user_id', $userKey->user_id)->first();

                $taxas = [
                    'taxa_cash_in' => $userData->taxa_cash_in ?? $taxa_cash_in,
                    'taxa_cash_in_fixa' => $userData->taxa_cash_in_fixa ?? $taxa_fixa_padrao,
                    'taxa_cash_out' => $userData->taxa_cash_out ?? $taxa_cash_out,
                    'taxa_cash_out_fixa' => $userData->taxa_cash_out_fixa ?? $taxa_fixa_padrao,
                    'taxa_fixa_padrao' => $taxa_fixa_padrao
                ];

                $taxafixa = $taxas['taxa_cash_in_fixa'] ?? $taxa_fixa_padrao;
                $taxa_percentual = $taxas['taxa_cash_in'] ?? $taxa_cash_in;

                try{
                    $taxatotal = ((float)$data->amount * (float)$taxa_percentual / 100);
                    $deposito_liquido = (float)$data->amount - $taxatotal;
                    $taxa_cash_in = $taxatotal;
                    $descricao = "PORCENTAGEM";
                } catch (DivisionByZeroException $e) {
                    $taxatotal = 0;
                    $deposito_liquido = (float)$data->amount;
                    $taxa_cash_in = 0;
                    $descricao = "PORCENTAGEM";
                }

                if (!is_null($taxafixa) && $taxafixa > 0) {
                    $deposito_liquido = $deposito_liquido - $taxafixa;
                    $taxa_cash_in = $taxa_cash_in + $taxafixa;
                }

                if ((float)$taxa_cash_in < (float)$app->baseline) {
                    $deposito_liquido = (float)$data->amount - (float)$app->baseline;
                    $taxa_cash_in = (float)$app->baseline;
                    $descricao = "FIXA";
                }

                $date = Carbon::now();

                $cashin = [
                    "user_id"                       => $data->user->username,
                    "externalreference"             => $responseData['txid'],
                    "amount"                        => $data->amount,
                    "client_name"                   => $data->debtor_name,
                    "client_document"               => $document,
                    "client_email"                  => $data->email,
                    "date"                     		=> $date,
                    "status"                        => 'WAITING_FOR_APPROVAL',
                    "idTransaction"                 => $responseData['txid'],
                    "deposito_liquido"              => $deposito_liquido,
                    "qrcode_pix"                    => $responseData['pixCopiaECola'],
                    "paymentcode"                   => $responseData['pixCopiaECola'],
                    "paymentCodeBase64"             => base64_encode($responseData['pixCopiaECola']),
                    "adquirente_ref"                => 'inter',
                    "taxa_cash_in"                  => $taxa_cash_in,
                    "taxa_pix_cash_in_adquirente"   => self::$taxaCashIn,
                    "taxa_pix_cash_in_valor_fixo"   => $taxafixa,
                    "client_telefone"               => $data->phone,
                    "executor_ordem"                => 'inter',
                    "descricao_transacao"           => $descricao,
                    "callback"                      => $data->postback,
                    "split_email"                   => null,
                    "split_percentage"              => null,
                ];

                Solicitacoes::create($cashin);

                return [
                    "data" => [
                        "idTransaction" => $responseData['txid'],
                        "qrcode" => $responseData['pixCopiaECola'],
                        "qr_code_image_url" => base64_encode($responseData['pixCopiaECola'])
                    ],
                    "status" => 200
                ];
            }
        } else {
            return [
                "data" => [
                    'status' => 'error'
                ],
                "status" => 401
            ];
        }
    }

    public static function requestPaymentInter($request)
    {
        $user = User::where('id', $request->user->id)->first();
        Helper::calculaSaldoLiquido($user->user_id);

        $app = App::first();
        $taxa_cash_in = $app->taxa_cash_in ?? 5;
        $taxa_cash_out = $app->taxa_cash_out ?? 5;
        $taxa_fixa_padrao = $app->taxa_fixa_padrao;

        $taxas = [
            'taxa_cash_in' => $user->taxa_cash_in ?? $taxa_cash_in,
            'taxa_cash_in_fixa' => $user->taxa_cash_in_fixa ?? $taxa_fixa_padrao,
            'taxa_cash_out' => $user->taxa_cash_out ?? $taxa_cash_out,
            'taxa_cash_out_fixa' => $user->taxa_cash_out_fixa ?? $taxa_fixa_padrao,
            'taxa_fixa_padrao' => $taxa_fixa_padrao
        ];

        try{
            $taxatotal = ((float)$request->amount * (float)$taxas['taxa_cash_out'] / 100);
            $cashout_liquido = (float)$request->amount - $taxatotal;
            $taxa_cash_out = $taxatotal;
            $descricao = "PORCENTAGEM";
        } catch (DivisionByZeroException $e) {
            // Tratar a exceção de divisão por zero
            $taxatotal = 0;
            $cashout_liquido = (float)$request->amount;
            $taxa_cash_out = 0;
            $descricao = "FIXA";
        }

        if (!is_null($taxas['taxa_cash_out_fixa']) && $taxas['taxa_cash_out_fixa'] > 0) {
            $cashout_liquido = $cashout_liquido - $taxas['taxa_cash_out_fixa'];
            $taxa_cash_out = $taxa_cash_out + $taxas['taxa_cash_out_fixa'];
        }

        if ((float)$taxa_cash_out < (float)$app->baseline) {
            $cashout_liquido = (float)$request->amount - (float)$app->baseline;
            $taxa_cash_out = (float)$app->baseline;
            $descricao = "FIXA";
        }

        if($user->tax_method == 'balance' && $request->baasPostbackUrl != 'web'){
            $cashout_liquido = $request->amount;
            $taxa_cash_out_balance = $taxa_cash_out;
            $taxa_cash_out = 0;
            $descricao = "DESCONTO SALDO";
        }

        if ($user->saldo < $cashout_liquido) {
            return response()->json([
                'status' => 'error',
                'message' => "Saldo insuficiente.",
            ], 401);
        }

        $date = Carbon::now();

        if ($request->baasPostbackUrl === 'web') {
            //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            //Helper::decrementAmount($user, $cashout_liquido, 'saldo');

            return self::generateTransactionPaymentManualInter($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
        }

        if (self::generateCredentialsInter()) {
            $payload = [
                "valor"         => $cashout_liquido,
                "destinatario"  => [
                    "tipo"      => 'CHAVE',
                    "chave"     => $request->pixKey,
                ]
            ];

            $bearerToken = self::authTokenInter();

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-id-idempotente' => Str::uuid()->toString(),
                'Authorization' => 'Bearer ' . $bearerToken
            ])->withOptions([
                'cert' => self::$certPath,
                'ssl_key' => self::$keyPath,
            ])
            ->post(self::$urlInter . '/banking/v2/pix', $payload);

            $nomeCompleto = explode(' ', $request->user->name, 2);
            if (count($nomeCompleto) < 2) {
                $nomeCompleto[1] = '';
            }

            // array:4 [ // app/Traits/InterTrait.php:333
            //     "tipoRetorno" => "APROVACAO" // PROCESSADO
            //     "codigoSolicitacao" => "6e2bc660-33e2-4805-b0f3-862223878aff"
            //     "dataPagamento" => "2025-09-08"
            //     "dataOperacao" => "2025-09-08"
            // ]

            if ($response->successful()) {
                //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
                //Helper::decrementAmount($user, $cashout_liquido, 'saldo');
                $responseData = $response->json();
                $name = $nomeCompleto[0] . ' ' . $nomeCompleto[1];

                if(!isset($responseData['codigoSolicitacao'])){
                    $mensagem = "Falha ao processar pagamento.";
                    if(isset($responseData['title'])){
                        $mensagem = $responseData['title'] . ": " . ($responseData['detail'] ?? '');
                    }

                    return [
                        "status" => 200,
                        "data" => [
                            "status" => "error",
                            "message"=> $mensagem
                        ]
                    ];
                }

                $pixKey = $request->pixKey;

                switch ($request->pixKeyType) {
                    case 'cpf':
                    case 'cnpj':
                    case 'phone':
                        $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                        break;
                }

                $statusPayment = 'PENDING';
                switch ($responseData['tipoRetorno'] ?? '') {
                    case 'PROCESSADO':
                        $statusPayment = 'COMPLETED';
                        break;
                }

                if ($statusPayment == 'PENDING') {
                    try {
                        @file_get_contents('https://xdroid.net/api/message?k=k-58fae46e84c1&t=Saque+Pendente+API&c=Saque+em+andamento+Inter&u=http%3A%2F%2Fgoogle.com.br');
                    } catch (\Throwable $e) {
                        // Ignora qualquer erro silenciosamente, sem log
                    }
                }

                $pixcashout = [
                    "user_id"               => $request->user->username,
                    "externalreference"     => $responseData['codigoSolicitacao'] ?? '',
                    "amount"                => $request->amount,
                    "beneficiaryname"       => $name,
                    "beneficiarydocument"   => $pixKey,
                    "pix"                   => $pixKey,
                    "pixkey"                => strtolower($request->pixKeyType),
                    "date"                  => $date,
                    "status"                => $statusPayment,
                    "type"                  => "PIX",
                    "idTransaction"         => $responseData['codigoSolicitacao'] ?? '',
                    "taxa_cash_out"         => $taxa_cash_out,
                    "cash_out_liquido"      => $cashout_liquido,
                    "end_to_end"            => $responseData['codigoSolicitacao'] ?? '',
                    "callback"              => $request->baasPostbackUrl,
                    "descricao_transacao"   => $descricao
                ];

                if($user->tax_method == 'balance'){
                    self::createTaxBalanceInter($pixcashout, $taxa_cash_out_balance);
                }

                $cashout = SolicitacoesCashOut::create($pixcashout);

                return [
                    "status" => 200,
                    "data" => [
                        "id"                => $responseData['codigoSolicitacao'] ?? '',
                        "amount"            => $request->amount,
                        "pixKey"            => $request->pixKey,
                        "pixKeyType"        => $request->pixKeyType,
                        "withdrawStatusId"  => $statusPayment == 'COMPLETED' ? 'paid' : "PendingProcessing",
                        "createdAt"         => $responseData['createdAt'] ?? $date,
                        "updatedAt"         => $responseData['updatedAt'] ?? $date
                    ]
                ];
            }else{
                return [
                    "status" => 200,
                    "data" => [
                        "status" => "error",
                        "message"=> isset($response['error']) ? $response['error'] : 'Erro ao processar pagamento.'
                    ]
                ];
            }
        } else {
            return [
                "status" => 200,
                "data" => [
                    "status" => "error"
                ]
            ];
        }
    }

    protected static function generateTransactionPaymentManualInter($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        $idTransaction = Str::uuid()->toString();
      	$name = $request->user->name;  
        $pixKey = $request->pixKey;

        switch ($request->pixKeyType) {
            case 'cpf':
            case 'cnpj':
            case 'phone':
                $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                break;
        }

        try {
            @file_get_contents('https://xdroid.net/api/message?k=k-58fae46e84c1&t=Saque+Pendente+ADMIN&c=Saque+em+andamento+Inter&u=http%3A%2F%2Fgoogle.com.br');
        } catch (\Throwable $e) {
            // Ignora qualquer erro silenciosamente, sem log
        }

        $pixcashout = [
            "user_id"               => $request->user->username,
            "externalreference"     => $idTransaction,
            "amount"                => $request->amount,
            "beneficiaryname"       => $name,
            "beneficiarydocument"   => $pixKey,
            "pix"                   => $pixKey,
            "pixkey"                => strtolower($request->pixKeyType),
            "date"                  => $date,
            "status"                => "PENDING",
            "type"                  => "PIX",
            "idTransaction"         => $idTransaction,
            "taxa_cash_out"         => $taxa_cash_out,
            "cash_out_liquido"      => $cashout_liquido,
            "end_to_end"            => $idTransaction,
            "callback"              => $request->baasPostbackUrl,
            "descricao_transacao"   => "WEB"
        ];

        $cashout = SolicitacoesCashOut::create($pixcashout);

        return [
            "status" => 200,
            "data" => [
                "id"                => $idTransaction,
                "amount"            => $request->amount,
                "pixKey"            => $request->pixKey,
                "pixKeyType"        => $request->pixKeyType,
                "withdrawStatusId"  => "PendingProcessing",
                "createdAt"         => $date,
                "updatedAt"         => $date
            ]
        ];
    }

    public static function liberarSaqueManualInter($id)
    {
        if (self::generateCredentialsInter()) {
            $cashout = SolicitacoesCashOut::where('id', $id)->first();
          
            $payload = [
                "valor"         => $cashout->cash_out_liquido,
                "destinatario"  => [
                    "tipo"      => 'CHAVE',
                    "chave"     => $cashout->pix,
                ]
            ];

            $bearerToken = self::authTokenInter();

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-id-idempotente' => Str::uuid()->toString(),
                'Authorization' => 'Bearer ' . $bearerToken
            ])->withOptions([
                'cert' => self::$certPath,
                'ssl_key' => self::$keyPath,
            ])
            ->post(self::$urlInter . '/banking/v2/pix', $payload);

            if(!isset($response['codigoSolicitacao'])){
                $mensagem = "Falha ao processar pagamento.";
                if(isset($response['title'])){
                    $mensagem = $response['title'] . ": " . ($response['detail'] ?? '');
                }

                return back()->with('error', $mensagem);
            }
            
            if ($response->successful()) {
                $responseData = $response->json();
                $pixcashout = [
                    "status"                => 'COMPLETED',
                    "externalreference"     => $responseData['codigoSolicitacao'],
                    "idTransaction"         => $responseData['codigoSolicitacao'],
                    "end_to_end"            => $responseData['codigoSolicitacao'],
                    "descricao_transacao"   => "LIBERADOADMIN"
                ];

                $cashout = SolicitacoesCashOut::where('id', $id)->update($pixcashout);
                return back()->with('success', 'Pedido de saque enviado com sucesso!');
            } else {
                return back()->with('error', 'Houve um erro ao liberar saque.');
            }
        }
    }
}
