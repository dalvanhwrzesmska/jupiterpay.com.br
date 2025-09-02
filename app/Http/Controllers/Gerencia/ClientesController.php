<?php

namespace App\Http\Controllers\Gerencia;

use App\Http\Controllers\Controller;
use App\Models\GerenteApoio;
use App\Models\Solicitacoes;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersKey;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientesController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->permission != 5) {
            return redirect()->to('/dashboard');
        }
        // Cadastrados hoje
        $usersHoje = User::where('gerente_id', auth()->id())->whereDate('created_at', Carbon::today())->count();

        // Cadastrados na semana
        $usersSemana = User::where('gerente_id', auth()->id())->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ])->count();

        // Cadastrados no mês
        $usersMes = User::where('gerente_id', auth()->id())->whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])->count();

        $users = User::where('gerente_id', auth()->id())->get();

        $comissoes = Transactions::where('gerente_id', auth()->user()->user_id)->sum('comission_value');

        $usersTotal = $users->count();
        return view('gerencia.index', compact(
            'users',
            'comissoes',
            'usersHoje',
            'usersSemana',
            'usersMes',
            'usersTotal'
        ));
    }

    public function relatorio(Request $request)
    {
        $query = DB::table('transactions')
            ->where('gerente_id', auth()->user()->id);

        // Filtro de busca
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('descricao', 'like', "%{$buscar}%")
                    ->orWhere('nome_cliente', 'like', "%{$buscar}%"); // Adapte para os campos reais
            });
        }

        // Filtro de período
        $periodo = $request->input('periodo', 'hoje');
        switch ($periodo) {
            case 'hoje':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'ontem':
                $query->whereDate('created_at', Carbon::yesterday());
                break;
            case '7dias':
                $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
                break;
            case '30dias':
                $query->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()]);
                break;
            case 'tudo':
                // Nenhum filtro de data
                break;
            case 'personalizado':
                // Exemplo se vier como "2024-05-01:2024-05-20"
                if (strpos($request->periodo, ':') !== false) {
                    [$inicio, $fim] = explode(':', $request->periodo);
                    $query->whereBetween('created_at', [$inicio, $fim]);
                }
                break;
            default:
                // Fallback para hoje se não reconhecido
                $query->whereDate('created_at', Carbon::today());
                break;
        }

        $transactions = $query->get();

        return view('gerencia.relatorio', compact('transactions'));
    }


    public function material(Request $request)
    {
        if (auth()->user()->permission != 5) {
            return redirect()->to('/dashboard');
        }

        $apoios = GerenteApoio::get();
        return view('gerencia.material', compact('apoios'));
    }

    public function detalhes($id, Request $request)
    {
        // Obter a data e hora atual usando Carbon
        $now = Carbon::now();

        // Início e fim do dia de hoje
        $todayStart = $now->copy()->startOfDay()->toDateTimeString();
        $todayEnd = $now->copy()->endOfDay()->toDateTimeString();

        // Início do mês
        $startOfMonth = $now->copy()->startOfMonth()->toDateTimeString();

        // Início da semana
        $startOfWeek = $now->copy()->startOfWeek()->toDateTimeString();

        // Consultas para obter os totais
        $totalCadastros = User::count();

        $cadastrosHoje = User::whereBetween('data_cadastro', [$todayStart, $todayEnd])
            ->count();

        $cadastrosMes = User::where('data_cadastro', '>=', $startOfMonth)
            ->count();

        $cadastrosSemana = User::where('data_cadastro', '>=', $startOfWeek)
            ->count();

        $usuario = User::find($id);
        return view('gerencia.clientedetalhe', compact('usuario'));
    }

    public function usuarioStatus(Request $request)
    {
        $message = "";
        $usuarioId = $request->input('id');
        $usuario = User::where('id', $usuarioId)->first();

        if ($request->tipo === 'status') {
            $status = $usuario->status == 5 ? 1 : 5;
            $message = $status == 5 ? "Status alterado para pendente!" : "Status alterado para Aprovado";
            $usuario->update(['status' => $status]);
        }

        if ($request->tipo === 'banido') {
            $banido = $usuario->banido == 1 ? 0 : 1;
            $message = $banido == 0 ? "Usuário desbanido com sucesso!" : "Usuário banido com sucesso!";
            $usuario->update(['banido' => $banido]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function edit($id, Request $request)
    {
        if (!isset($id)) {
            return redirect()->back()->with('error', "Selecione um usuário!");
        }

        $email = $request->input('email');
        $name = $request->input('name');

        $token = $request->input('token');
        $secret = $request->input('secret');


        $user = User::find($id);

        if (!isset($user)) {
            return redirect()->back()->with('error', "Usuário não encontrado!");
        }

        if ($user->email != $email) {
            $validation = $request->validate([
                'email' => ['unique:users,email'],
            ]);

            if (!$validation) {
                return redirect()->back()->with('error', "Email já cadastrado na base!");
            }
        }

        $payl = [
            'email' => $email,
            'name' => $name
        ];

        if (!is_null($request->password)) {
            $payl['password'] = Hash::make($request->input('password'));
        }

        User::where('id', $id)->update($payl);

        $userkey = UsersKey::where('user_id', $user->user_id)->first();
        if (is_null($userkey)) {
            $user_id = $user->user_id;
            $userkey = UsersKey::create(compact('user_id', 'token', 'secret'));
        }

        $userkey->update(compact('token', 'secret'));

        return redirect()->back()->with('success', "Usuário alterado com sucesso!");
    }

    /*  public function resetsenha($id, Request $reques)
    {
        $user = User::find($id);

        $newPassword = Str::random(10);
        $user->password = Hash::make($newPassword);
        $user->password_temp = true;
        $user->save();

        // Envia e-mail
        Mail::to($user->email)->send(new \App\Mail\NewPasswordMail($user, $newPassword));

        return back()->with('success', 'Senha enviada com sucesso.');
    } */
}
