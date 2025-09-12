<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Adquirente;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersKey;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\WhatsappController;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('id', '>=', 1); // cria query base

        $status = $request->query('status');
        $buscar = $request->query('buscar');

        switch ($status) {
            case 'ativos':
                $users->where('banido', 0);
                break;
            case 'banidos':
                $users->where('banido', 1);
                break;
            case 'pendentes':
                $users->where('status', 5);
                break;
        }
        if (isset($buscar)) {
            $users->where('name', "LIKE", "%$buscar%");
        }

        $users = $users->get(); // executa a query e pega os resultados
        $gerentes = User::where('permission', 5)->get();
        $gateways = Adquirente::all();
        return view('admin.usuarios', compact('users', 'gerentes', 'gateways'));
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
        return view('admin.usuariodetalhes', compact('usuario'));
    }

    public function usuarioStatus(Request $request)
    {
        $message = "";
        $usuarioId = $request->input('id');
        $usuario = User::where('id', $usuarioId)->first();

        if ($request->tipo === 'status') {
            $status = ($usuario->status == 5 || $usuario->status == 0) ? 1 : 5;
            $message = $status == 5 ? "Status alterado para pendente!" : "Status alterado para Aprovado";

            if($status == 1) {
                $whatsapp = new WhatsappController();
                $whatsapp->sendMessage($usuario->telefone, 'conta_aprovada');
            }

            $usuario->update(['status' => $status]);
        }

        if ($request->tipo === 'banido') {
            $banido = $usuario->banido == 1 ? 0 : 1;
            $message = $usuario->banido == 1 ? "Usuário desbanido com sucesso!" : "Usuário banido com sucesso!";
            $usuario->update(['banido' => $banido]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy($id, Request $request)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', "Usuário não encontrado!");
        }

        $user->delete($id);
        return redirect()->route('admin.usuarios')->with('error', "Usuário removido com sucesso!");
    }

    public function edit($id, Request $request)
    {
        if (!isset($id)) {
            return redirect()->back()->with('error', "Selecione um usuário!");
        }

        $dataFull = $request->all();

        $email = $request->input('email');
        $name = $request->input('name');
        $permission = $request->input('permission');
        $cpf_cnpj = $request->input('cpf_cnpj');
        $telefone = $request->input('telefone');
        $data_nascimento = $request->input('data_nascimento');
        $gateway_cashin = $request->input('gateway_cashin');
        $gateway_cashout = $request->input('gateway_cashout');

        $token = $request->input('token');
        $secret = $request->input('secret');

        $user = User::find($id);

        if (!isset($user)) {
            return redirect()->back()->with('error', "Usuário não encontrado!");
        }

        if (!empty($cpf_cnpj) && $user->cpf_cnpj != $cpf_cnpj) {
            $validation = $request->validate([
                'cpf_cnpj' => ['unique:users,cpf_cnpj'],
            ]);

            if (!$validation) {
                return redirect()->back()->with('error', "CPF já cadastrado na base!");
            }
        }

        if (!empty($email) && $user->email != $email) {
            $validation = $request->validate([
                'email' => ['unique:users,email'],
            ]);

            if (!$validation) {
                return redirect()->back()->with('error', "Email já cadastrado na base!");
            }
        }

        $payl = [];

        $taxa_cartao_1x = $request->input('taxa_cartao_parcela[1]') ?? null;
        $taxa_cartao_2x = $request->input('taxa_cartao_parcela[2]') ?? null;
        $taxa_cartao_3x = $request->input('taxa_cartao_parcela[3]') ?? null;
        $taxa_cartao_4x = $request->input('taxa_cartao_parcela[4]') ?? null;
        $taxa_cartao_5x = $request->input('taxa_cartao_parcela[5]') ?? null;
        $taxa_cartao_6x = $request->input('taxa_cartao_parcela[6]') ?? null;
        $taxa_cartao_7x = $request->input('taxa_cartao_parcela[7]') ?? null;
        $taxa_cartao_8x = $request->input('taxa_cartao_parcela[8]') ?? null;
        $taxa_cartao_9x = $request->input('taxa_cartao_parcela[9]') ?? null;
        $taxa_cartao_10x = $request->input('taxa_cartao_parcela[10]') ?? null;
        $taxa_cartao_11x = $request->input('taxa_cartao_parcela[11]') ?? null;
        $taxa_cartao_12x = $request->input('taxa_cartao_parcela[12]') ?? null;

        for ($i = 0; $i < 13; $i++) {
            $campo = 'taxa_cartao_' . $i . 'x';
            if (isset($$campo) && !empty($$campo)) {
                $payl[$campo] = $$campo;
            }
        }

        $items = [
            'email', 'name', 'permission', 'cpf_cnpj', 'data_nascimento', 'telefone', 'gateway_cashin', 'gateway_cashout', 
            'taxa_produto_checkout_fixa', 'taxa_produto_checkout_percentual', 'taxa_cash_out', 'taxa_boleto_fixa', 'taxa_boleto_percentual', 'taxa_checkout_fixa', 'taxa_checkout_porcentagem',
            'taxa_cash_in_fixa', 'taxa_cash_out_fixa', 'tax_method', 'taxa_cash_in', 'taxa_percentual', 'baseline_cash_in', 'baseline_cash_out'
        ];

        foreach ($items as $item) {
            if (isset($dataFull[$item]) && !empty($dataFull[$item])) {
                $payl[$item] = $request->input($item) ?? null;
            }
        }

        $path = uniqid();
        if ($request->hasFile('foto_rg_frente')) {
            $fotoRgFrente = Helper::salvarArquivo($request, 'foto_rg_frente', $path);
            $payl['foto_rg_frente'] = $fotoRgFrente;
        }

        if ($request->hasFile('foto_rg_verso')) {
            $fotoRgVerso  = Helper::salvarArquivo($request, 'foto_rg_verso', $path);
            $payl['foto_rg_verso'] = $fotoRgVerso;
        }

        if ($request->hasFile('selfie_rg')) {
            $selfieRg     = Helper::salvarArquivo($request, 'selfie_rg', $path);
            $payl['selfie_rg'] = $selfieRg;
        }

        if (!is_null($request->password)) {
            $payl['password'] = Hash::make($request->input('password'));
        }

        if ($request->filled('gerente_percentage')) {
            $valorFormatado = $request->input('gerente_percentage'); // ex: "1.234,56"
            $valorParaBanco = number_format($valorFormatado, 2); // "1234.56"
            $payl['gerente_percentage'] = (float) $valorParaBanco;
        }

        if ($request->filled('gerente_id')) {
            $payl['gerente_id'] = $request->input('gerente_id');
        }

        $payl['gerente_aprovar'] = $request->has('gerente_aprovar');

        //dd($payl);

        User::where('id', $id)->update($payl);

        $userkey = UsersKey::where('user_id', $user->user_id)->first();
        if (!$userkey) {
            $user_id = $user->user_id;
            $token = rand(11111, 999999);
            $secret = rand(11111, 999999);
            
            $userkey = UsersKey::create(compact('user_id', 'token', 'secret'));
        }
        
        if(empty($token) || empty($secret)){
            $secret = $userkey->secret;
            $token = $userkey->token;
        }

        $userkey->update(compact('token', 'secret'));

        return redirect()->back()->with('success', "Usuário alterado com sucesso!");
    }

    public function modalDinamico($tipo, $id)
    {
        $user = User::with(['chaves', 'depositos'])->findOrFail($id);
        $gerentes = User::where('permission', 5)->get();
        $gateways = Adquirente::all();

        switch ($tipo) {
            case 'visualizar':
                return view('admin.usuarios.partials.modal-visualizar', compact('user'))->render();
            case 'editar':
                return view('admin.usuarios.partials.modal-edit', compact('user', 'gerentes', 'gateways'))->render();
            case 'trocar-senha':
                return view('admin.usuarios.partials.modal-trocar-senha', compact('user'))->render();
            case 'aprovar':
                return view('admin.usuarios.partials.modal-aprovar', compact('user'))->render();
            case 'banir':
                return view('admin.usuarios.partials.modal-banir', compact('user'))->render();
            case 'delete':
                return view('admin.usuarios.partials.modal-delete', compact('user'))->render();
            default:
                abort(404);
        }
    }
}
