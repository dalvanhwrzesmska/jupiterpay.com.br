<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\App;
use App\Models\User;
use App\Models\Cashtime;
use Faker\Factory as FakerFactory;
use App\Helpers\Helper;

trait PodPayTrait
{
    protected static string $clientId;
    protected static string $secret;
    protected static string $secretWithdrawal;
    protected static string $urlCashIn;
    protected static string $urlCashOut;
    protected static string $taxaCashIn;
    protected static string $taxaCashOut;
    
    // COMPLETED | PROCESSING | CANCELLED | REFUSED | PENDING_ANALYSIS | PENDING_QUEUE

    protected static function generateCredentialsPodpay()
    {

        $setting = Cashtime::first();
        if (!$setting) {
            return false;
        }

        self::$clientId = 'pk_lAzPXYJXLSnIR1AUj0VqFtDqlwSUph1LuRLU7ueLaYEtXm6_';
        self::$secret = 'sk_t1rcOP9dE8nL2rfc97Ha9tFDKXhz5Qo_xKDauZ7L5UgAjKHn';
        self::$secretWithdrawal = 'wk_Boa1VfS76nNCMHY_gEPwWcCEcn7-DKj4IrXvefdLuzvl4-Yf';
        self::$urlCashIn = 'https://api.podpay.co/v1/transactions';
        self::$urlCashOut = 'https://api.podpay.co/v1/transfers';
        self::$taxaCashIn = $setting->taxa_pix_cash_in;
        self::$taxaCashOut = $setting->taxa_pix_cash_out;

        return true;
    }

    public static function requestDepositPodpay($data)
    {
        if (self::generateCredentialsPodpay()) {
            $client_ip = $data->ip();

            $productid = uniqid();
            $document = Helper::generateValidCpf();
            
            $payload = [
              "amount" => $data->amount,
              "currency" => "BRL",
              "paymentMethod" => "pix",
              "items" => [
                [
                  "title" => "Produto teste",
                  "unitPrice" => $data->amount,
                  "quantity" => 1,
                  "tangible" => false
                ]
              ],
              "customer" => [
                "name" => $data->debtor_name,
                "email" => $data->email,
                "document" => [
                  "number" => $document,
                  "type" => "global"
                ]
              ],
              "postbackUrl" => url("podpay/callback/deposit"),
              "externalRef" => "externalRefpapi"
            ];
            
            $basic = base64_encode(self::$clientId . ':' . self::$secret);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $basic,
            ])->post(self::$urlCashIn, $payload);

            if ($response->successful()) {

                $responseData = $response->json();
                $setting = App::first();
                $user = $data->user;

                $taxa_cash_in_user = $user->taxa_cash_in;
                if(floatval($taxa_cash_in_user) > 0){
                    $setting->taxa_cash_in_padrao = $taxa_cash_in_user;
                }

                $taxafixa = $setting->taxa_fixa_padrao;
                $taxatotal = ((float)$data->amount * (float)$setting->taxa_cash_in_padrao / 100);
                $deposito_liquido = (float)$data->amount - $taxatotal;
                $taxa_cash_in = $taxatotal;
                $descricao = "PORCENTAGEM";

                if ((float)$taxatotal < (float)$setting->baseline) {
                    $deposito_liquido = (float)$data->amount - (float)$setting->baseline;
                    $taxa_cash_in = (float)$setting->baseline;
                    $descricao = "FIXA";
                }

                if (!is_null($taxafixa) && $taxafixa > 0) {
                    $deposito_liquido = $deposito_liquido - $taxafixa;
                    $taxa_cash_in = $taxa_cash_in + $taxafixa;
                }

                $date = Carbon::now();

                $cashin = [
                    "user_id"                       => $data->user->username,
                    "externalreference"             => $responseData['id'],
                    "amount"                        => $data->amount,
                    "client_name"                   => $data->debtor_name,
                    "client_document"               => $document,
                    "client_email"                  => $data->email,
                    "date"                     		=> $date,
                    "status"                        => 'WAITING_FOR_APPROVAL',
                    "idTransaction"                 => $responseData['id'],
                    "deposito_liquido"              => $deposito_liquido,
                    "qrcode_pix"                    => $responseData['pix']['qrcode'],
                    "paymentcode"                   => $responseData['pix']['qrcode'],
                    "paymentCodeBase64"             => base64_encode($responseData['pix']['qrcode']),
                    "adquirente_ref"                => 'podpay',
                    "taxa_cash_in"                  => $taxa_cash_in,
                    "taxa_pix_cash_in_adquirente"   => self::$taxaCashIn,
                    "taxa_pix_cash_in_valor_fixo"   => $taxafixa,
                    "client_telefone"               => $data->phone,
                    "executor_ordem"                => 'podpay',
                    "descricao_transacao"           => $descricao,
                    "callback"                      => $data->postback,
                    "split_email"                   => null,
                    "split_percentage"              => null,
                ];

                Solicitacoes::create($cashin);

                return [
                    "data" => [
                        "idTransaction" => $responseData['idTransaction'],
                        "qrcode" => $responseData['paymentCode'],
                        "qr_code_image_url" => $responseData['paymentCodeBase64']
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

    public static function requestPaymentPodpay($request)
    {
        $user = User::where('id', $request->user->id)->first();

        $setting = App::first();
        $taxafixa = $setting->taxa_fixa_padrao_cash_out ?? 0;

        $taxatotal = ((float)$request->amount * (float)$setting->taxa_cash_out_padrao / 100);
        $cashout_liquido = (float)$request->amount - $taxatotal;
        $taxa_cash_out = $taxatotal;
        $descricao = "PORCENTAGEM";

        if ((float)$taxatotal < (float)$setting->baseline) {
            $cashout_liquido = (float)$request->amount - (float)$setting->baseline;
            $taxa_cash_out = (float)$setting->baseline;
            $descricao = "FIXA";
        }

        if (!is_null($taxafixa) && $taxafixa > 0) {
            $cashout_liquido = $cashout_liquido - $taxafixa;
            $taxa_cash_out = $taxa_cash_out + $taxafixa;
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

            return self::generateTransactionPaymentManualPodpay($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
        }

        if (self::generateCredentialsPodpay()) {
            $callback = url("podpay/callback/withdraw");
            $client_ip = $request->ip();
            
            $payload = [
              "method" => "fiat",
              "amount" => $request->amount,
              "netPayout" => false,
              "pixKey" => $request->pixKey,
              "pixKeyType" => $request->pixKeyType,
              "postbackUrl" => $callback
            ];

            $basic = base64_encode(self::$clientId . ':' . self::$secret);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $basic,
                'x-withdraw-key' => self::$secretWithdrawal
            ])->post(self::$urlCashOut, $payload);


            if ($response->successful()) {
                //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
                //Helper::decrementAmount($user, $cashout_liquido, 'saldo');

                $name = explode(' ', $request->user->name)[0] . ' ' . explode(' ', $request->user->name)[1];
                $responseData = $response->json();

                $pixKey = $request->pixKey;

                switch ($request->pixKeyType) {
                    case 'cpf':
                    case 'cnpj':
                    case 'phone':
                        $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                        break;
                }


                $pixcashout = [
                    "user_id"               => $request->user->username,
                    "externalreference"     => $responseData['id'],
                    "amount"                => $request->amount,
                    "beneficiaryname"       => $name,
                    "beneficiarydocument"   => $pixKey,
                    "pix"                   => $pixKey,
                    "pixkey"                => strtolower($request->pixKeyType),
                    "date"                  => $date,
                    "status"                => $responseData["status"] ?? "PENDING",
                    "type"                  => "PIX",
                    "idTransaction"         => $responseData['id'],
                    "taxa_cash_out"         => $taxa_cash_out,
                    "cash_out_liquido"      => $cashout_liquido,
                    "end_to_end"            => $responseData['id'],
                    "callback"              => $request->baasPostbackUrl,
                    "descricao_transacao"   => $descricao
                ];

                $cashout = SolicitacoesCashOut::create($pixcashout);

                return [
                    "status" => 200,
                    "data" => [
                        "id"                => $responseData['id'],
                        "amount"            => $request->amount,
                        "pixKey"            => $request->pixKey,
                        "pixKeyType"        => $request->pixKeyType,
                        "withdrawStatusId"  => $responseData["status"] ?? "PendingProcessing",
                        "createdAt"         => $responseData['createdAt'] ?? $date,
                        "updatedAt"         => $responseData['updatedAt'] ?? $date
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

    protected static function generateTransactionPaymentManualPodpay($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
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

    public static function liberarSaqueManualPodpay($id)
    {
        if (self::generateCredentialsJupiter()) {
            $cashout = SolicitacoesCashOut::where('id', $id)->first();
            $callback = url("podpay/callback/withdraw");

            $payload = [
                "amount"            => intval($cashout->amount * 100),
                "pixKey"            => $cashout->pix,
                "pixKeyType"        => $cashout->pixkey == 'aleatoria' ? 'random' : $cashout->pixkey,
                "baasPostbackUrl"   => $callback
            ];
          
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-authorization-key' => self::$secret,
            ])->post(self::$urlCashOut, $payload);
			//dd($response->json());

            if ($response->successful()) {
                $responseData = $response->json();
                $pixcashout = [
                    "externalreference"     => $responseData['id'],
                    "idTransaction"         => $responseData['id'],
                    "end_to_end"            => $responseData['id'],
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
