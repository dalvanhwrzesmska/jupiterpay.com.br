<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolicitacoesCashOut;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use App\Traits\CashtimeTrait;
use App\Traits\PradaPayTrait;
use App\Traits\InterTrait;

class SaquesController extends Controller
{
    public function index(Request $request)
    {
        $limit = 10;

        // Página atual
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $limit;

        $totalRecords = DB::table('solicitacoes_cash_out')
            ->where('descricao_transacao', "WEB")
            ->where('status', 'PENDING')->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consultar os registros com paginação
        $saques = DB::table('solicitacoes_cash_out')
            ->where('descricao_transacao', "WEB")
            ->where('status', 'PENDING')
            ->orderByDesc('id')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return view("admin.aprovar-saques", compact('saques', 'page', 'totalRecords', 'totalPages'));
    }

    public function aprovar($id, Request $request)
    {
        if (auth()->user()->permission != 3) {
            return back()->with("error", "Usuário sem permissões.");
        }

        $saques = SolicitacoesCashOut::find($id);
        if (!$saques) {
            return back()->with("error", "Solicitação de saque não encontrado.");
        }

        $user = User::where('user_id', $saques->user_id)->first();

        switch($user->gateway_cashout) {
            case 'pradapay':
                return PradaPayTrait::liberarSaqueManualPradaPay($id);
            case 'inter':
                return InterTrait::liberarSaqueManualInter($id);
            default:
                return back()->with('error', 'Gateway de saque não suportado.');
        }
    }

    public function rejeitar($id, Request $request)
    {
        if (auth()->user()->permission != 3) {
            return back()->with("error", "Usuário sem permissões.");
        }

        $saque = SolicitacoesCashOut::where('id', $id)->first();
        if (!$saque) {
            return back()->with("error", "Solicitação de saque não encontrado.");
        }

        $saque->update(['status' => 'CANCELLED']);
        $user = User::where('user_id', $saque->user_id)->first();
        $user->increment('transacoes_recused', 1);
        $user->decrement('saldo_bloqueado', $saque->amount);
        $user->save();

        Helper::calculaSaldoLiquido($user->user_id);

        return back()->with('success', 'Solicitação rejeitada com sucesso.');
    }
}
