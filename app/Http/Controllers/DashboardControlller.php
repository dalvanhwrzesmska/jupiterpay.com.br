<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\CheckoutBuild;

class DashboardControlller extends Controller
{
    public function index(Request $request)
    {
        Helper::calculaSaldoLiquido(auth()->user()->user_id);
        
        //dd(Auth::user());

        $userId = Auth::user()->user_id;
        $nome = Auth::user()->name;
        $status = Auth::user()->status;
        $permission = Auth::user()->permission;
        $banido = Auth::user()->banido;
        $produtoFiltro = $request->input('produto', 'todos');
        $periodoFiltro = $request->input('periodo', 'tudo');

        [$startDate, $endDate] = $this->resolvePeriodo($periodoFiltro);

        // Base queries
        $solicitacoes = DB::table('solicitacoes')->where('user_id', $userId);
        $solicitacoesPaid = DB::table('solicitacoes')->where('user_id', $userId)->where('status', 'PAID_OUT');
        $solicitacoesCashOut = DB::table('solicitacoes_cash_out')->where('user_id', $userId);
        $sumDepositoLiquidoQuery = DB::table('solicitacoes')->where('user_id', $userId)->where('status', 'PAID_OUT');
        $sumSaquesAprovadosQuery = DB::table('solicitacoes_cash_out')->where('user_id', $userId)->where('status', 'COMPLETED');

        // Aplica filtros de data e produto
        $this->aplicarFiltros($solicitacoes, $startDate, $endDate, $produtoFiltro);
        $this->aplicarFiltros($solicitacoesPaid, $startDate, $endDate, $produtoFiltro);
        $this->aplicarFiltros($solicitacoesCashOut, $startDate, $endDate, $produtoFiltro);
        $this->aplicarFiltros($sumDepositoLiquidoQuery, $startDate, $endDate, $produtoFiltro);
        $this->aplicarFiltros($sumSaquesAprovadosQuery, $startDate, $endDate, $produtoFiltro);

        // Executa e coleta resultados
        $ultimasSolicitacoes = (clone $solicitacoes)->where('status', 'PAID_OUT')->orderByDesc('id')->limit(4)->get();
        $ultimosSaques = (clone $solicitacoesCashOut)->where('status', 'COMPLETED')->orderByDesc('id')->limit(4)->get();

        $ultimasTransacoes = $ultimasSolicitacoes->merge($ultimosSaques)
            ->sortByDesc(fn($item) => Carbon::parse($item->date))
            ->take(4);
      
      	if(!is_null($startDate) && !is_null($endDate)){
 			$ultimasTransacoes = $ultimasSolicitacoes->merge($ultimosSaques)
            ->whereBetween('date', [$startDate, $endDate])
            ->sortByDesc(fn($item) => Carbon::parse($item->date))
            ->take(4);
      
        }

        // Totais
        $totalPaidOut = $solicitacoesPaid->count();
        $totalRequests = $solicitacoes->count();
        $sumAmountPaidOut = $solicitacoesPaid->sum('amount');
        $sumDepositoLiquido = $sumDepositoLiquidoQuery->sum('deposito_liquido');
        $sumSaquesAprovados = $sumSaquesAprovadosQuery->sum('cash_out_liquido');

        $saldoliquido = (float) $sumDepositoLiquido - (float) $sumSaquesAprovados;

        // Dados para gráfico
        $result_solicitacoes = DB::table('solicitacoes')
            ->selectRaw("DATE(date) as dia, SUM(amount) as valor")
            ->where('user_id', $userId)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $dates = $result_solicitacoes->pluck('dia')->toArray();
        $values = $result_solicitacoes->pluck('valor')->toArray();

        $realDate = now()->format('d/m/Y');

        // Produtos disponíveis (opcional: pode vir de config, tabela, etc.)
        $produtos = CheckoutBuild::where('user_id', auth()->user()->user_id); // ajuste conforme seu sistema

		$solicitacoes = (clone $solicitacoes);

        $ver = $request->segment(1);
        $ver = 'v2';
        $viewName = $ver === 'v2' ? 'dashboard-v2.index' : 'dashboard';

        return view($viewName, compact(
            'nome',
            'status',
            'banido',
            'result_solicitacoes',
            'permission',
            'solicitacoes',
            'solicitacoesPaid',
            'totalPaidOut',
            'totalRequests',
            'sumAmountPaidOut',
            'sumDepositoLiquido',
            'sumSaquesAprovados',
            'ultimasTransacoes',
            'saldoliquido',
            'realDate',
            'dates',
            'values',
            'produtos'
        ));
    }

    private function aplicarFiltros($query, $startDate, $endDate, $produtoFiltro)
    {
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        if ($produtoFiltro !== 'todos') {
            $query->where('descricao_transacao', 'PRODUTO');
        }

        return $query;
    }

    private function resolvePeriodo($periodo)
    {
        $hoje = Carbon::today();

        return match ($periodo) {
            'hoje'     => [$hoje, $hoje->copy()->endOfDay()],
            'ontem'    => [Carbon::yesterday(), Carbon::yesterday()->copy()->endOfDay()],
            '7dias'    => [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()],
            '30dias'   => [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()->endOfDay()],
            default    => str_contains($periodo, ':')
                ? explode(':', $periodo) // espera-se que venham datas no formato '2024-01-01:2024-01-15'
                : [null, null],
        };
    }
}
