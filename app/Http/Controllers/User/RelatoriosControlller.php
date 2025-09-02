<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitacoesCashOut;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RelatoriosControlller extends Controller
{
    public function pixentrada(Request $request)
    {
        $userId = Auth::user()->user_id;

        $periodo = $request->input('periodo');
        $dataInicio = null;
        $dataFim = null;

        switch ($periodo) {
            case 'hoje':
                $dataInicio = Carbon::today()->toDateString();
                $dataFim = Carbon::today()->toDateString();
                break;

            case 'ontem':
                $dataInicio = Carbon::yesterday()->toDateString();
                $dataFim = Carbon::yesterday()->toDateString();
                break;

            case '7dias':
                $dataInicio = Carbon::today()->subDays(6)->toDateString();
                $dataFim = Carbon::today()->toDateString();
                break;

            case '30dias':
                $dataInicio = Carbon::today()->subDays(29)->toDateString();
                $dataFim = Carbon::today()->toDateString();
                break;

            case 'tudo':
                // Sem filtro de data
                break;

            case 'personalizado':
                $person = explode(':', $periodo);
                $dataInicio = $person[0];
                $dataFim = $person[1];
                break;

            default:
                if (Str::contains($periodo, ':')) {
                    $person = explode(':', $periodo);
                    $dataInicio = $person[0] ?? null;
                    $dataFim = $person[1] ?? null;
                } else {
                    $dataInicio = Carbon::today()->toDateString();
                    $dataFim = Carbon::today()->toDateString();
                }
                break;
        }

        $buscar = $request->input('buscar');

        $transactions = DB::table('solicitacoes')
            ->where('user_id', $userId)
            ->when($dataInicio && $dataFim, function ($query) use ($dataInicio, $dataFim) {
                return $query->whereBetween('date', [$dataInicio, $dataFim]);
            })
            ->when($buscar, function ($query) use ($buscar) {
                return $query->where(function ($q) use ($buscar) {
                    $q->where('client_name', 'like', "%{$buscar}%")
                        ->orWhere('idTransaction', 'like', "%{$buscar}%")
                        ->orWhere('client_email', 'like', "%{$buscar}%")
                        ->orWhere('client_document', 'like', "%{$buscar}%");
                });
            })
            ->orderByDesc('date')
            ->get();

        $ver = $request->segment(1);
        $viewName = $ver === 'v2' ? 'dashboard-v2.profile.pixentrada' : 'profile.pixentrada';
        return view($viewName, compact('transactions'));
    }

    public function pixsaida(Request $request)
    {
        $userId = Auth::user()->user_id;

        $periodo = $request->input('periodo');
        $dataInicio = null;
        $dataFim = null;

        switch ($periodo) {
            case 'hoje':
                $dataInicio = Carbon::today()->toDateString();
                $dataFim = Carbon::today()->toDateString();
                break;

            case 'ontem':
                $dataInicio = Carbon::yesterday()->toDateString();
                $dataFim = Carbon::yesterday()->toDateString();
                break;

            case '7dias':
                $dataInicio = Carbon::today()->subDays(6)->toDateString();
                $dataFim = Carbon::today()->toDateString();
                break;

            case '30dias':
                $dataInicio = Carbon::today()->subDays(29)->toDateString();
                $dataFim = Carbon::today()->toDateString();
                break;

            case 'tudo':
                // Sem filtro de data
                break;

            case 'personalizado':
                $person = explode(':', $periodo);
                $dataInicio = $person[0];
                $dataFim = $person[1];
                break;

            default:
                if (Str::contains($periodo, ':')) {
                    $person = explode(':', $periodo);
                    $dataInicio = $person[0] ?? null;
                    $dataFim = $person[1] ?? null;
                } else {
                    $dataInicio = Carbon::today()->toDateString();
                    $dataFim = Carbon::today()->toDateString();
                }
                break;
        }

        $buscar = $request->input('buscar');

        $transactions = DB::table('solicitacoes_cash_out')
            ->where('user_id', $userId)
            ->when($dataInicio && $dataFim, function ($query) use ($dataInicio, $dataFim) {
                return $query->whereBetween('date', [$dataInicio, $dataFim]);
            })
            ->when($buscar, function ($query) use ($buscar) {
                return $query->where(function ($q) use ($buscar) {
                    $q->where('beneficiaryname', 'like', "%{$buscar}%")
                        ->orWhere('idTransaction', 'like', "%{$buscar}%")
                        ->orWhere('beneficiarydocument', 'like', "%{$buscar}%");
                });
            })
            ->orderByDesc('date')
            ->get();

        $ver = $request->segment(1);
        $viewName = $ver === 'v2' ? 'dashboard-v2.profile.pixsaida' : 'profile.pixsaida';
        return view($viewName, compact('transactions'));
    }

    public function consulta(Request $request)
    {
        // Pega os filtros de data
        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');

        // Configurações de paginação
        $limit = 100; // Número de registros por página
        $page = $request->input('page', 1); // Página atual
        $offset = ($page - 1) * $limit;

        // Consulta para obter a soma filtrada com status COMPLETED
        $filteredQuery = DB::table('solicitacoes_cash_out')
            ->where('status', 'COMPLETED');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $filteredQuery->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $total_cash_out_liquido_filtrado = $filteredQuery->sum('cash_out_liquido');
        $total_cash_out_bruto_filtrada = $filteredQuery->sum('amount');
        $lucro_plataforma_filtrada = $total_cash_out_bruto_filtrada - $total_cash_out_liquido_filtrado;

        // Consulta para obter o número total de registros, ajustando para o filtro de datas
        $countQuery = DB::table('solicitacoes_cash_out')
            ->where('status', 'COMPLETED');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $countQuery->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $totalRecords = $countQuery->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consulta para obter os registros com paginação e filtro de data
        $transactions = DB::table('solicitacoes_cash_out')
            ->where('status', 'COMPLETED')
            ->when($dataInicio && $dataFim, function ($query) use ($dataInicio, $dataFim) {
                return $query->whereBetween('date', [$dataInicio, $dataFim]);
            })
            ->orderByDesc('date')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $ver = $request->segment(1);
        $viewName = $ver === 'v2' ? 'dashboard-v2.profile.consulta' : 'profile.consulta';
        return view($viewName, compact(
            "transactions",
            "total_cash_out_liquido_filtrado",
            "total_cash_out_bruto_filtrada",
            "lucro_plataforma_filtrada",
            "totalPages",
            "page",
            "dataInicio",
            "dataFim"
        ));
    }
}
