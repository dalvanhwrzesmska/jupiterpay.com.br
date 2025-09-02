<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solicitacoes;
use App\Models\ConfirmarDeposito;
use App\Models\SolicitacoesCashOut;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransacoesController extends Controller
{
    public function index(Request $request)
    {
        $limit = 10;

        // Página atual
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $limit;

        $now = Carbon::now();

        $todayStart = $now->startOfDay();
        $todayEnd = $now->endOfDay();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfWeek = $now->copy()->startOfWeek();

        // Valores de depósitos
        $depositsPaidOutToday = Solicitacoes::where('status', 'PAID_OUT')
            ->whereBetween('date', [$todayStart, $todayEnd])
            ->sum('amount');

        $depositsPaidOutMonth = Solicitacoes::where('status', 'PAID_OUT')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('amount');

        $depositsPaidOutTotal = Solicitacoes::where('status', 'PAID_OUT')->sum('amount');

        $pixGeneratedTotal = Solicitacoes::whereIn('status', ['PAID_OUT', 'WAITING_FOR_APPROVAL'])
            ->sum('amount');

        $totalRecords = DB::table('solicitacoes')
            ->where('status', "PAID_OUT")->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consultar os registros com paginação
        $deposits = DB::table('solicitacoes')
            //->where('status', "PAID_OUT")
            ->orderByDesc('date')
            ->offset($offset)
            ->limit($limit)
            ->get();


        $transacoes_aprovadas = Solicitacoes::where('status', 'PAID_OUT')->count() + SolicitacoesCashOut::where('status', 'COMPLETED')->count();
        $lucro_liquido_hoje = Solicitacoes::where('status', 'PAID_OUT')->whereDate('date', Carbon::today())->sum(DB::raw('amount - deposito_liquido')) + SolicitacoesCashOut::where('status', 'COMPLETED')->whereDate('date', Carbon::today())->sum(DB::raw('amount - cash_out_liquido'));
        $lucro_liquido_mes = Solicitacoes::where('status', 'PAID_OUT')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum(DB::raw('amount - deposito_liquido')) + SolicitacoesCashOut::where('status', 'COMPLETED')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum(DB::raw('amount - cash_out_liquido'));
        $lucro_liquido_total = Solicitacoes::where('status', 'PAID_OUT')->sum(DB::raw('amount - deposito_liquido')) + SolicitacoesCashOut::where('status', 'COMPLETED')->sum(DB::raw('amount - cash_out_liquido'));

        $transacoes_aprovadas = Solicitacoes::where('status', 'PAID_OUT')->count();
        $valor_aprovado_hoje = Solicitacoes::where('status', 'PAID_OUT')->whereDate('date', Carbon::today())->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->whereDate('date', Carbon::today())->sum('amount');
        $valor_aprovado_mes = Solicitacoes::where('status', 'PAID_OUT')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum('amount');
        $valor_aprovado_total = Solicitacoes::where('status', 'PAID_OUT')->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->sum('amount');


        return view("admin.financeiro.transacoes", compact(
            "transacoes_aprovadas",
            "lucro_liquido_hoje",
            "lucro_liquido_mes",
            "lucro_liquido_total",
            "transacoes_aprovadas",
            "valor_aprovado_hoje",
            "valor_aprovado_mes",
            "valor_aprovado_total",

            "depositsPaidOutToday",
            "depositsPaidOutMonth",
            "depositsPaidOutTotal",
            "pixGeneratedTotal",
            'deposits',
            'totalPages',
            'page'
        ));
    }
}
