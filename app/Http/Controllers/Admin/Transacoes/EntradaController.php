<?php

namespace App\Http\Controllers\Admin\Transacoes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Retiradas;
use Illuminate\Support\Str;
use App\Models\Solicitacoes;

class EntradaController extends Controller
{
    public function index()
    {

        $saldoBaixo = 5;
        $users = User::all();

        $totalSaqueAprovado = Retiradas::where('status', 1)
            ->sum('valor_liquido');


        $totalSaquePendente = Retiradas::where('status', 0)
            ->sum('valor_liquido');

        $saldoliquido = Retiradas::sum('valor_liquido') ?? 0;
        return view("admin.transacoes.criarentrada", compact(
            "users",
            "saldoBaixo",
            "totalSaqueAprovado",
            "totalSaquePendente",
            "saldoliquido",

        ));
    }

    public function addentrada(Request $request)
    {
        $valor = floatval($request->valor);
        $user_id = $request->user_id;

        $user = User::where('user_id', $user_id)->first();
        $date = date('Y-m-d H:i:s');

        $uuid = Str::uuid()->toString();
        $idTransaction = str_replace('-', '', $uuid);

        $data = [
            "user_id" => $user_id,
            "externalreference" => env('APP_NAME') . '_' . $idTransaction,
            "amount" => $valor,
            "client_name" => $user->name,
            "client_document" => $user->cpf_cnpj,
            "client_email" => $user->email,
            "date" => $date,
            "status" => "PAID_OUT",
            "idTransaction" => $idTransaction,
            "deposito_liquido" => $valor,
            "qrcode_pix" => '',
            "paymentcode" => '',
            "paymentCodeBase64" => '',
            "adquirente_ref" => env('APP_NAME'),
            "taxa_cash_in" => $user->taxa_cash_in,
            "taxa_pix_cash_in_adquirente" => 0,
            "taxa_pix_cash_in_valor_fixo" => 0,
            "client_telefone" => $user->telefone,
            "executor_ordem" => env('APP_NAME'),
            "descricao_transacao" => "MANUAL",
        ];

        Solicitacoes::create($data);
        return redirect()->intended(route('admin.transacoes.entradas', absolute: false))->with('success', "Transação criada com sucesso!");
    }
}
