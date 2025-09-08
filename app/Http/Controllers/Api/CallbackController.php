<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Solicitacoes;
use App\Models\User;
use App\Models\SolicitacoesCashOut;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\App;
use App\Models\CheckoutOrders;
use App\Models\Transactions;

class CallbackController extends Controller
{
    public function callbackDeposit(Request $request)
    {
        $data = $request->all();
        if ($data['status'] == "paid") {

            if(!isset($data['orderId']) && isset($data['idTransaction']))
            {
                $data['orderId'] = $data['idTransaction'];
            }

            if(empty($data['orderId']))
            {
                Log::error("[PIX-IN] Callback sem orderId ou idTransaction");
                return response()->json(['status' => false]);
            }

            $cashin = Solicitacoes::where('idTransaction', $data['orderId'])->first();
            if (!$cashin || $cashin->status != "WAITING_FOR_APPROVAL") {
                return response()->json(['status' => false]);
            }

            $updated_at = Carbon::now();
            $cashin->update(['status' => 'PAID_OUT', 'updated_at' => $updated_at]);

            $user = User::where('user_id', $cashin->user_id)->first();
            Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
            Helper::calculaSaldoLiquido($user->user_id);

            $gerente = User::where('id', $user->gerente_id)->first();

          	if ($gerente && isset($user->gerente_id) && !is_null($user->gerente_id) && isset($gerente->gerente_percentage) && $gerente->gerente_percentage > 0) {
            	$gerente_porcentagem = $gerente->gerente_percentage;
                $valor = (float) $cashin->taxa_cash_in * (float) $gerente_porcentagem / 100;

                Transactions::create([
                    'user_id' => $user->user_id,
                    'gerente_id' => $user->gerente_id,
                    'solicitacao_id' => $cashin->id,
                    'comission_value' => $valor,
                    'transaction_percent' => $cashin->taxa_cash_in,
                    'comission_percent' => $gerente_porcentagem,
                ]);


                Helper::calculaSaldoLiquido($gerente->user_id);
            }

            $order = CheckoutOrders::where('idTransaction', $data['orderId'])->first();
            if ($order) {
                $order->update(['status' => 'pago']);
            }

            if ($cashin->callback) {
                $payload = [
                    "status"            => "paid",
                    "idTransaction"     => $cashin->idTransaction,
                    "typeTransaction"   => "PIX"
                ];

                Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'accept' => 'application/json'
                ])->post($cashin->callback, $payload);

                \Log::debug("[PIX-IN] Send Callback: Para $cashin->callback -> Enviando...");
                if ($cashin->callback && $cashin->callback != 'web') {
                    $payload = [
                        "status"            => "paid",
                        "idTransaction"     => $cashin->idTransaction,
                        "typeTransaction"   => "PIX"
                    ];

                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashin->callback, $payload);

                    $success = 'paid';
                    return response()->json(['status' => $success]);
                } else {
                    $order = CheckoutOrders::where('idTransaction', $data['orderId'])->first();
                    if ($order) {
                        $order->update(['status' => 'pago']);
                    }
                }
            }
        }
    }

    public function callbackWithdraw(Request $request)
    {
        $data = $request->all();

        \Log::debug("[PIX-OUT] Received Callback: " . json_encode($data));

        if (
            (isset($data['withdrawStatusId']) && $data['withdrawStatusId'] == "Successfull") || 
            (isset($data['status']) && $data['status'] == "pago")
        ) {
            $id = null;
            if (isset($data['status']) && $data['status'] == "pago" && isset($data['idTransaction'])) {
                $id = $data['idTransaction'];
                $data['updatedAt'] = Carbon::now();
            }

            if (empty($id) && isset($data['withdrawStatusId'])) {
                $id = $data['id'];
            }

            $cashout = SolicitacoesCashOut::where('idTransaction', $id)->first();
            if (!$cashout || $cashout->status != "PENDING") {
                return response()->json(['status' => false]);
            }

            $cashout->update(['status' => 'COMPLETED', 'updated_at' => $data['updatedAt']]);
            $user = User::where('user_id', $cashout->user_id)->first();

            Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');

            if ($cashout->callback) {
                $payload = [
                    "status"            => "paid",
                    "idTransaction"     => $cashout->idTransaction,
                    "typeTransaction"   => "PAYMENT"
                ];

                $sendcallback = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'accept' => 'application/json'
                ])->post($cashout->callback, $payload);

                \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callback -> Enviando...");
                if ($cashout->callback && $cashout->callback != 'web') {
                    $payload = [
                        "status"            => "paid",
                        "idTransaction"     => $cashout->idTransaction,
                        "typeTransaction"   => "PAYMENT"
                    ];

                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashout->callback, $payload);

                    return response()->json(['status' => 'paid']);
                }
            }
        }

        if (isset($data['status']) && $data['status'] == "refunded") {

            $id = null;
            if (isset($data['status']) && $data['status'] == "refunded" && isset($data['idTransaction'])) {
                $id = $data['idTransaction'];
                $data['updatedAt'] = Carbon::now();
            }

            if (empty($id) && isset($data['withdrawStatusId'])) {
                $id = $data['id'];
            }

            $cashout = SolicitacoesCashOut::where('idTransaction', $id)->first();
            $cashout_tax = SolicitacoesCashOut::where('idTransaction', $id.'_TAX')->first();

            if(!$cashout){
                return response()->json(['status' => false]);
            }

            $cashout->update(['status' => 'CANCELLED', 'updated_at' => $data['updatedAt']]);
            $user = User::where('user_id', $cashout->user_id)->first();
            Helper::incrementAmount($user, $cashout->amount, 'saldo');

            if ($cashout_tax) {
                $cashout_tax->update(['status' => 'CANCELLED', 'updated_at' => $data['updatedAt']]);
                Helper::incrementAmount($user, $cashout_tax->cash_out_liquido, 'saldo');
            }

            if ($cashout->callback) {
                $payload = [
                    "status"            => "cancelled",
                    "idTransaction"     => $cashout->idTransaction,
                    "typeTransaction"   => "PAYMENT"
                ];

                $sendcallback = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'accept' => 'application/json'
                ])->post($cashout->callback, $payload);

                \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callback -> Enviando...");
                if ($cashout->callback && $cashout->callback != 'web') {
                    $payload = [
                        "status"            => "cancelled",
                        "idTransaction"     => $cashout->idTransaction,
                        "typeTransaction"   => "PAYMENT"
                    ];

                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashout->callback, $payload);

                    return response()->json(['status' => 'cancelled']);
                }
            }
        }
    }
}
