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
        $userId   = Auth::user()->user_id;
        $periodo  = trim((string)$request->input('periodo'));
        $buscar   = trim((string)$request->input('buscar'));
    
        $dataInicio = null;
        $dataFim    = null;
    
        switch ($periodo) {
            case 'hoje':
                $dataInicio = now()->startOfDay();
                $dataFim    = now()->endOfDay();
                break;
            case 'ontem':
                $dataInicio = now()->subDay()->startOfDay();
                $dataFim    = now()->subDay()->endOfDay();
                break;
            case '7dias':
                $dataInicio = now()->subDays(6)->startOfDay(); // últimos 7 dias incluindo hoje
                $dataFim    = now()->endOfDay();
                break;
            case '30dias':
                $dataInicio = now()->subDays(29)->startOfDay(); // últimos 30 dias incluindo hoje
                $dataFim    = now()->endOfDay();
                break;
            case 'tudo':
                // sem filtro de data
                break;
            case 'personalizado':
                // Se preferir mandar datas em inputs separados:
                // $ini = $request->input('data_inicio');
                // $fim = $request->input('data_fim');
                // Aqui vou assumir que você manda periodo = "2025-09-01:2025-09-06"
            default:
                if (str_contains($periodo, ':')) {
                    [$ini, $fim] = explode(':', $periodo) + [null, null];
                    if ($ini && $fim) {
                        // Como estamos usando Y-m-d, Carbon::parse resolve direto
                        $dataInicio = \Carbon\Carbon::parse($ini)->startOfDay();
                        $dataFim    = \Carbon\Carbon::parse($fim)->endOfDay();
                    }
                }
                break;
        }
    
        // Configurações de paginação
        $limit = 20; // Número de registros por página
        $page = $request->input('page', 1); // Página atual
        $offset = ($page - 1) * $limit;
    
        $query = DB::table('solicitacoes')
            ->where('user_id', $userId);
    
        if ($dataInicio && $dataFim) {
            $query->whereBetween('date', [$dataInicio, $dataFim]);
        }
    
        if ($buscar !== '') {
            $like = "%{$buscar}%";
            $query->where(function ($q) use ($like, $buscar) {
                $q->where('client_name', 'like', $like)
                  ->orWhere('idTransaction', 'like', $like)
                  ->orWhere('client_email', 'like', $like)
                  ->orWhere('client_document', 'like', $like);
    
                if (ctype_digit($buscar)) {
                    $q->orWhere('idTransaction', (int)$buscar);
                }
            });
        }

        // Consulta para os totais (sem paginação)
        $allTransactions = (clone $query)->get();
        
        // Consulta para obter o número total de registros
        $totalRecords = $query->count();
        $totalPages = ceil($totalRecords / $limit);
    
        // Consulta com paginação
        $transactions = $query
            ->orderByDesc('date')
            ->offset($offset)
            ->limit($limit)
            ->get();
    
        $ver = $request->segment(1);
        $viewName = $ver === 'v2'
            ? 'dashboard-v2.profile.pixentrada'
            : 'profile.pixentrada';
    
        return view($viewName, compact('transactions', 'allTransactions', 'totalPages', 'page', 'periodo', 'buscar', 'totalRecords'));
    }
    
    public function pixsaida(Request $request)
    {
        $userId  = Auth::user()->user_id;
        $periodo = trim((string)$request->input('periodo'));
        $buscar  = trim((string)$request->input('buscar'));
    
        $dataInicio = null;
        $dataFim    = null;
    
        switch ($periodo) {
            case 'hoje':
                $dataInicio = now()->startOfDay();
                $dataFim    = now()->endOfDay();
                break;
            case 'ontem':
                $dataInicio = now()->subDay()->startOfDay();
                $dataFim    = now()->subDay()->endOfDay();
                break;
            case '7dias':
                $dataInicio = now()->subDays(6)->startOfDay(); // últimos 7 dias incluindo hoje
                $dataFim    = now()->endOfDay();
                break;
            case '30dias':
                $dataInicio = now()->subDays(29)->startOfDay(); // últimos 30 dias incluindo hoje
                $dataFim    = now()->endOfDay();
                break;
            case 'tudo':
                // sem filtro de data
                break;
            default:
                if (str_contains($periodo, ':')) {
                    [$ini, $fim] = explode(':', $periodo) + [null, null];
    
                    // Se você só usa Y-m-d basta isso:
                    $parse = function ($d) {
                        if (!$d) return null;
                        try { return \Carbon\Carbon::parse($d); } catch (\Throwable $e) { return null; }
                    };
    
                    // (OPCIONAL) Para também aceitar dd/mm/YYYY, descomente:
                    /*
                    $parse = function ($d) {
                        if (!$d) return null;
                        $d = trim($d);
                        try { return \Carbon\Carbon::createFromFormat('Y-m-d', $d); } catch (\Throwable $e) {}
                        try { return \Carbon\Carbon::createFromFormat('d/m/Y', $d); } catch (\Throwable $e) {}
                        try { return \Carbon\Carbon::parse($d); } catch (\Throwable $e) {}
                        return null;
                    };
                    */
    
                    $cIni = $parse($ini);
                    $cFim = $parse($fim);
    
                    if ($cIni && $cFim) {
                        $dataInicio = $cIni->startOfDay();
                        $dataFim    = $cFim->endOfDay();
                    }
                }
                break;
        }
    
        // Configurações de paginação
        $limit = 20; // Número de registros por página
        $page = $request->input('page', 1); // Página atual
        $offset = ($page - 1) * $limit;
    
        $query = DB::table('solicitacoes_cash_out')
            ->where('user_id', $userId);
    
        if ($dataInicio && $dataFim) {
            $query->whereBetween('date', [$dataInicio, $dataFim]);
        }
    
        if ($buscar !== '') {
            $like = "%{$buscar}%";
            $query->where(function ($q) use ($like, $buscar) {
                $q->where('beneficiaryname', 'like', $like)
                  ->orWhere('idTransaction', 'like', $like)
                  ->orWhere('beneficiarydocument', 'like', $like);
    
                if (ctype_digit($buscar)) {
                    $q->orWhere('idTransaction', (int)$buscar);
                }
            });
        }

        // Consulta para os totais (sem paginação)
        $allTransactions = (clone $query)->get();
        
        // Consulta para obter o número total de registros
        $totalRecords = $query->count();
        $totalPages = ceil($totalRecords / $limit);
    
        // Consulta com paginação
        $transactions = $query
            ->orderByDesc('date')
            ->offset($offset)
            ->limit($limit)
            ->get();
    
        $ver = $request->segment(1);
        $viewName = $ver === 'v2'
            ? 'dashboard-v2.profile.pixsaida'
            : 'profile.pixsaida';
    
        return view($viewName, compact('transactions', 'allTransactions', 'totalPages', 'page', 'periodo', 'buscar', 'totalRecords'));
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
