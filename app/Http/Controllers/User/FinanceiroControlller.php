<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Solicitacoes;
use App\Models\Retiradas;
use App\Models\App;
use App\Models\User;
use App\Models\SolicitacoesCashOut;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class FinanceiroControlller extends Controller
{
    public function index()
    {

        $userData = User::where('user_id', Auth::user()->user_id)->first();
        $user_id = Auth::user()->user_id;

        // Consultar as últimas 4 solicitações do usuário
        $solicitacoes = Solicitacoes::where('user_id', $user_id)
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get(['id', 'externalreference', 'amount', 'deposito_liquido', 'client_name', 'client_document', 'client_email', 'date', 'status', 'paymentcode']);

        // Consultar o número de solicitações com status 'PAID_OUT' para o usuário
        $totalPaidOut = Solicitacoes::where('user_id', $user_id)
            ->where('status', 'PAID_OUT')
            ->count();

        // Consultar o número total de solicitações para o usuário
        $totalRequests = Solicitacoes::where('user_id', $user_id)
            ->count();

        // Consultar a soma dos valores de 'amount' para as solicitações com status 'PAID_OUT' e o user_id correspondente
        $sumAmountPaidOut = Solicitacoes::where('user_id', $user_id)
            ->where('status', 'PAID_OUT')
            ->sum('amount') ?: 0;

        // Consultar a soma dos valores de 'deposito_liquido' para as solicitações com status 'PAID_OUT' e o user_id correspondente
        $sumDepositoLiquido = Solicitacoes::where('user_id', $user_id)
            ->where('status', 'PAID_OUT')
            ->sum('deposito_liquido') ?: 0;

        // Consultar a data real mais recente
        $realDate = Solicitacoes::where('user_id', $user_id)
            ->max('date');

        // Consultar a soma dos saques aprovados para o usuário
        $sumSaquesAprovados = SolicitacoesCashOut::where('user_id', $user_id)
            ->where('status', 'COMPLETED')
            ->sum('amount') ?: 0;

        $retiradas = Retiradas::where('user_id', $user_id)
            ->where('status', 0)
            ->count();

        $retiradasPendentes = $retiradas > 0;

        $totalDepositoLiquido = Solicitacoes::where('user_id', $user_id)
            ->where('status', 'PAID_OUT')
            ->sum('deposito_liquido');

        // Soma dos saques aprovados com status "COMPLETED"
        $totalSaquesAprovados = SolicitacoesCashOut::where('user_id', $user_id)
            ->where('status', 'COMPLETED')
            ->sum('cash_out_liquido');

        // Cálculo do saldo líquido
        $saldoliquido = (float) $totalDepositoLiquido - (float) $totalSaquesAprovados;

        $saldoBaixo = $saldoliquido < 5;
        $email = Auth::user()->email;

        Helper::calculaSaldoLiquido(auth()->user()->user_id);
        $setting = Helper::getSetting();

        $app = App::first();
        $taxa_cash_in = $app->taxa_cash_in ?? 5;
        $taxa_cash_out = $app->taxa_cash_out ?? 5;
        $taxa_fixa_padrao = $app->taxa_fixa_padrao;

        $taxas = [
            'taxa_cash_in' => $userData->taxa_cash_in ?? $taxa_cash_in,
            'taxa_cash_in_fixa' => $userData->taxa_cash_in_fixa ?? $taxa_fixa_padrao,
            'taxa_cash_out' => $userData->taxa_cash_out ?? $taxa_cash_out,
            'taxa_cash_out_fixa' => $userData->taxa_cash_out_fixa ?? $taxa_fixa_padrao,
            'taxa_fixa_padrao' => $taxa_fixa_padrao
        ];

        $request = request();
        $ver = $request->segment(1);
        $viewName = $ver === 'v2' ? 'dashboard-v2.profile.financeiro' : 'profile.financeiro';

        return view($viewName, compact(
            'email',
            'retiradasPendentes',
            "solicitacoes",
            'saldoBaixo',
            "totalPaidOut",
            "totalRequests",
            "sumAmountPaidOut",
            "sumDepositoLiquido",
            "realDate",
            "sumSaquesAprovados",
            "saldoliquido",
            "setting",
            "taxas"
        ));
    }

}
