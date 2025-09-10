<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\User;
use App\Models\Cashtime;
use App\Helpers\Helper;
use App\Models\UsersKey;

trait JupiterPayTrait
{
    protected static string $secret;
    protected static string $urlCashIn;
    protected static string $urlCashOut;
    protected static string $taxaCashIn;
    protected static string $taxaCashOut;

    protected static function generateCredentialsJupiter()
    {

        $setting = Cashtime::first();
        if (!$setting) {
            return false;
        }

        self::$secret = $setting->secret;
        self::$urlCashIn = 'https://api.jupiterpay.com.br/v1/cashin/';//$setting->url_cash_in;
        self::$urlCashOut = 'https://api.jupiterpay.com.br/v1/cashout/';//$setting->url_cash_out;
        self::$taxaCashIn = $setting->taxa_pix_cash_in;
        self::$taxaCashOut = $setting->taxa_pix_cash_out;

        return true;
    }

    public static function requestDepositJupiterpay($data)
    {
        if (self::generateCredentialsJupiter()) {
            $client_ip = $data->ip();
            $productid = uniqid();
            $document = Helper::generateValidCpf();

            $payload = [
                "postback"   => url("jupiterpay/callback/deposit"),
                "api-key"    => 'f1d78a80afab2a4c1e3dda0f',
                "client"      => [
                    "name"     => $data->debtor_name,
                    "email"    => $data->email,
                    "telefone"    => $data->phone,
                    "document" => $document
                ],
                "amount" => $data->amount
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-authorization-key' => self::$secret,
            ])->post(self::$urlCashIn, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                $userKey = UsersKey::where(['token' => $data->token, 'secret' => $data->secret])->first();
                $taxas = Helper::calcularTaxas($userKey->user_id, $data->amount);

                $deposito_liquido = $taxas['cash_in_liquido'];
                $taxa_cash_in = $taxas['cash_in_padrao'];
                $taxafixa = $taxas['taxa_fixa_padrao'];
                $descricao = $taxas['taxa_fixa_padrao'] > 0 ? "FIXA" : "PORCENTAGEM";

                $date = Carbon::now();

                $cashin = [
                    "user_id"                       => $data->user->username,
                    "externalreference"             => $responseData['idTransaction'],
                    "amount"                        => $data->amount,
                    "client_name"                   => $data->debtor_name,
                    "client_document"               => $document,
                    "client_email"                  => $data->email,
                    "date"                     		=> $date,
                    "status"                        => 'WAITING_FOR_APPROVAL',
                    "idTransaction"                 => $responseData['idTransaction'],
                    "deposito_liquido"              => $deposito_liquido,
                    "qrcode_pix"                    => $responseData['paymentCode'],
                    "paymentcode"                   => $responseData['paymentCode'],
                    "paymentCodeBase64"             => $responseData['paymentCodeBase64'],
                    "adquirente_ref"                => 'jupiterpay',
                    "taxa_cash_in"                  => $taxa_cash_in,
                    "taxa_pix_cash_in_adquirente"   => self::$taxaCashIn,
                    "taxa_pix_cash_in_valor_fixo"   => $taxafixa,
                    "client_telefone"               => $data->phone,
                    "executor_ordem"                => 'jupiterpay',
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

    public static function requestPaymentJupiterpay($request)
    {
        $user = User::where('id', $request->user->id)->first();
        Helper::calculaSaldoLiquido($user->user_id);
        $taxas = Helper::calcularTaxas($user->user_id, $request->amount);

        $cashout_liquido = $taxas['cash_out_liquido'];
        $taxa_cash_out = $taxas['cash_out_taxa'];

        $descricao = $taxas['taxa_fixa_padrao_cash_out'] > 0 ? "FIXA" : "PORCENTAGEM";

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
            return self::generateTransactionPaymentManualJupiter($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
        }

        if (self::generateCredentialsJupiter()) {
            $callback = url("jupiterpay/callback/withdraw");
            $client_ip = $request->ip();

            $payload = [
                "amount"            => floatval($request->amount * 100),
                "pixKey"            => $request->pixKey,
                "pixKeyType"        => $request->pixKeyType,
                "baasPostbackUrl"   => $callback
            ];


            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-authorization-key' => self::$secret,
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
                    "status"                => "PENDING",
                    "type"                  => "PIX",
                    "idTransaction"         => $responseData['id'],
                    "taxa_cash_out"         => $taxa_cash_out,
                    "cash_out_liquido"      => $cashout_liquido,
                    "end_to_end"            => $responseData['id'],
                    "callback"              => $request->baasPostbackUrl,
                    "descricao_transacao"   => $descricao
                ];

                if($user->tax_method == 'balance'){
                    Helper::createTaxBalance($pixcashout, $taxa_cash_out_balance);
                }

                $cashout = SolicitacoesCashOut::create($pixcashout);

                return [
                    "status" => 200,
                    "data" => [
                        "id"                => $responseData['id'],
                        "amount"            => $request->amount,
                        "pixKey"            => $request->pixKey,
                        "pixKeyType"        => $request->pixKeyType,
                        "withdrawStatusId"  => $responseData["PendingProcessing"] ?? "PendingProcessing",
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

    protected static function generateTransactionPaymentManualJupiter($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
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

    public static function liberarSaqueManualJupiter($id)
    {
        if (self::generateCredentialsJupiter()) {
            $cashout = SolicitacoesCashOut::where('id', $id)->first();
            $callback = url("jupiterpay/callback/withdraw");

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
