<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Solicitacoes;
use Carbon\Carbon;

class EntradasController extends Controller
{
    public function index(Request $request)
    {
        $dataHoje = Carbon::today()->toDateString();
        $mesAtual = Carbon::now()->format('Y-m');

        $totalaprovadasHoje = $this->contarTransacoes('PAID_OUT', $dataHoje);
        $totalaprovadasMes = $this->contarTransacoes('PAID_OUT', null, $mesAtual);
        $totalaprovadas = $this->contarTransacoes('PAID_OUT');
        $totalaprovadas = $this->contarTransacoes();

        $valorAprovadoHoje = $this->somarValores('amount', 'PAID_OUT', $dataHoje);
        $valorAprovadoMes = $this->somarValores('amount', 'PAID_OUT', null, $mesAtual);
        $valorAprovadoTotal = $this->somarValores('amount', 'PAID_OUT');

        $valorSaqueAprovadoHoje = $this->somarValores('deposito_liquido', 'PAID_OUT', $dataHoje);
        $valorSaqueAprovadoMes = $this->somarValores('deposito_liquido', 'PAID_OUT', null, $mesAtual);
        $valorSaqueAprovadoTotal = $this->somarValores('deposito_liquido', 'PAID_OUT');

        $totalsolicitacoes = Solicitacoes::count();

        $limit = 10; // Número de registros por página
        $page = $request->input('page', 1); // Página atual
        $offset = ($page - 1) * $limit;

        // Filtros de data
        $dataInicio = $request->input('data_inicio', '');
        $dataFim = $request->input('data_fim', '');

        // Query para obter a soma filtrada com status PAID_OUT
        $query = Solicitacoes::where('status', 'PAID_OUT');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $query->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $totalResults = $query->selectRaw('SUM(deposito_liquido) AS total_deposito_liquido_filtrado, SUM(amount) AS total_deposito_bruto_filtrada')->first();

        $total_deposito_liquido_filtrado = $totalResults->total_deposito_liquido_filtrado ?: 0;
        $total_deposito_bruto_filtrada = $totalResults->total_deposito_bruto_filtrada ?: 0;

        $lucro_plataforma_filtrada = $total_deposito_bruto_filtrada - $total_deposito_liquido_filtrado;

        // Consulta para obter o número total de registros
        $totalRecords = Solicitacoes::where('status', 'PAID_OUT');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $totalRecords->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $totalRecords = $totalRecords->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consulta para obter os registros com paginação e filtro de data
        $cashOuts = Solicitacoes::where('status', 'PAID_OUT')
            ->when(!empty($dataInicio) && !empty($dataFim), function ($query) use ($dataInicio, $dataFim) {
                return $query->whereBetween('date', [$dataInicio, $dataFim]);
            })
            ->orderBy('date', 'desc')
            ->paginate($limit);

        // Retornar a view com os dados
        return view('admin.financeiro.entradas', compact(
            'totalaprovadasHoje',
            'totalaprovadasMes',
            'totalaprovadas',
            'totalaprovadas',
            'valorAprovadoHoje',
            'valorAprovadoMes',
            'valorAprovadoTotal',
            'valorSaqueAprovadoHoje',
            'valorSaqueAprovadoMes',
            'valorSaqueAprovadoTotal',
            'totalsolicitacoes',
            'cashOuts',
            'total_deposito_liquido_filtrado',
            'total_deposito_bruto_filtrada',
            'lucro_plataforma_filtrada',
            'totalPages',
            'page',
            'limit',
            'dataInicio',
            'dataFim'
        ));
    }

    private function contarTransacoes($status = null, $data = null, $mes = null)
    {
        return DB::table('solicitacoes')
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($data, fn($query) => $query->whereDate('date', $data))
            ->when($mes, fn($query) => $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$mes]))
            ->count();
    }

    private function somarValores($campo, $status = null, $data = null, $mes = null)
    {
        return DB::table('solicitacoes')
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($data, fn($query) => $query->whereDate('date', $data))
            ->when($mes, fn($query) => $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$mes]))
            ->sum($campo) ?? 0;
    }
}
