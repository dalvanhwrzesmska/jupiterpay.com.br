<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CheckoutBuild;
use App\Models\CheckoutDepoimento;
use App\Models\CheckoutOrders;
use App\Models\SolicitacoesCashOut;
use App\Models\Solicitacoes;
use App\Models\UsersKey;
use App\Models\User;
use App\Traits\ApiTrait;
use App\Traits\CashtimeTrait;
use App\Traits\JupiterPayTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutControlller extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $query = CheckoutBuild::where("user_id", auth()->id());

        if (!is_null($buscar)) {
            $query->where('produto_name', 'LIKE', "%$buscar%");
        }

        $checkouts = $query->get();

        $ver = $request->segment(1);
        $viewName = $ver === 'v2' ? 'dashboard-v2.profile.checkout.index' : 'profile.checkout.index';

        return view($viewName, compact("checkouts"));
    }
    public function indexEdit($id, Request $request)
    {
        $ver = $request->segment(1);
        $viewName = $ver === 'v2' ? 'dashboard-v2.profile.checkout.edit' : 'profile.checkout.edit';

        $checkout = CheckoutBuild::where('id_unico', $id)->first();
        return view($viewName, compact('checkout'));
    }

    public function v1($id, Request $request)
    {
        $checkout = CheckoutBuild::where("id_unico", $id)->first();
        $user = User::where('id', $checkout->user_id)->first();
        $keys = UsersKey::where('user_id', $user->user_id)->first();
        $token = $keys->token;
        $secret = $keys->secret;

        return view('profile.checkout.v1', compact('checkout', 'secret', 'token'));
    }

    public function v2($id, Request $request)
    {
        $produto = CheckoutBuild::where("id_unico", $id)->first();
        $user = User::where('id', $produto->user_id)->first();
        $keys = UsersKey::where('user_id', $user->user_id)->first();
        $token = $keys->token;
        $secret = $keys->secret;

        return view('profile.checkout.v2', compact('produto', 'secret', 'token'));
    }

    public function create(Request $request)
    {

        $validated = $request->validate([
            "produto_name" => "required|string",
            "produto_valor" => "required|string",
            "produto_descricao" => "required|string",
            "produto_tipo" => "required|string",
            "produto_tipo_cob" => "required|string"
        ]);

        $data = $request->except(['_token', '_method', '/checkout']);

        $data['user_id'] = auth()->id();
        $data['id_unico'] = Str::uuid();

        CheckoutBuild::create($data);
        return redirect()->back()->with('success', 'Checkout cadastrado com sucesso com sucesso!');
    }

    public function edit($id, Request $request)
    {
        // Criamos o registro sem as imagens
        $checkoutBuild = CheckoutBuild::where('id', $id)->first();
        $checkoutDir = public_path("/checkouts/{$checkoutBuild->id}/");
        if (!file_exists($checkoutDir)) {
            mkdir($checkoutDir, 0755, true);
        }
        $data = collect($request->all())
            ->reject(function ($value, $key) {
                return preg_match('/^checkout_depoimentos_/', $key)
                    || in_array($key, ['_token', '_method', 'checkout_depoimentos_id', 'checkout_depoimentos_nome', 'checkout_depoimentos_depoimento', 'checkout_depoimentos_image']);
            })
            ->toArray();
    
        // Atualiza campos principais
        $checkoutBuild->update($data);
    
        // Atualiza imagens únicas como produto/banner/logo/etc
        $images_checkout = ['produto_image', 'checkout_header_logo', 'checkout_header_image', 'checkout_banner'];
        $allowedExtensions = ['png', 'jpeg', 'jpg', 'pdf'];
        $dataImg = [];
    
        foreach ($images_checkout as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $extension = strtolower($file->getClientOriginalExtension());
    
                if (!in_array($extension, $allowedExtensions)) {
                    return redirect()->back()
                        ->with('error', 'Extensão de arquivo não permitida para ' . $field . '. Envie png, jpeg, jpg ou pdf.');
                }
    
                $filename = 'checkout_' . $field . '.' . $extension;
                $file->move($checkoutDir, $filename);
                $dataImg[$field] = "/checkouts/{$checkoutBuild->id}/{$filename}";
            }
        }
    
        // Atualiza imagens únicas, se houver
        if (!empty($dataImg)) {
            $checkoutBuild->update($dataImg);
        }
    
        $checkoutBuild->fill([
            'checkout_timer_active' => $request->has('checkout_timer_active'),
            'checkout_header_logo_active' => $request->has('checkout_header_logo_active'),
            'checkout_header_image_active' => $request->has('checkout_header_image_active'),
            'checkout_topbar_active' => $request->has('checkout_topbar_active'),
            // outros campos...
        ])->save();
    
        return redirect()->back()->with('success', 'Checkout alterado com sucesso!');
    }

    public function destroy($id)
    {
        // Buscar o checkout pelo ID
        $checkout = CheckoutBuild::find($id);

        if (!$checkout) {
            return redirect()->back()->with('error', 'Checkout não encontrado.');
        }

        // Verifica se o usuário autenticado pode excluir esse checkout
        /* if (auth()->user()->user_id !== $checkout->user_id) {
            return redirect()->back()->with('error', 'Você não tem permissão para excluir este checkout.');
        } */

        // Deleta as imagens associadas, se existirem
        if ($checkout->logo_produto) {
            Storage::disk('public')->delete($checkout->logo_produto);
        }
        if ($checkout->banner_produto) {
            Storage::disk('public')->delete($checkout->banner_produto);
        }

        // Exclui o checkout do banco de dados
        $checkout->delete();

        return redirect()->back()->with('success', 'Checkout excluído com sucesso!');
    }

    public function gerarPedido(Request $request)
    {
        $data = $request->except(['_token']);
        $venda = CheckoutOrders::create($data);

        if (!$venda) {
            return response()->json(['status' => 'error', 'message' => 'Houve um erro. Tente novamente!']);
        }

        $checkout = CheckoutBuild::where('id', $venda->checkout_id)->first();
        $user = User::where('id', $checkout->user_id)->first();
        $chaves = UsersKey::where('user_id', $user->user_id)->first();

        $dataRequest = [
            'token' => $chaves->token,
            'secret' => $chaves->secret,
            'amount' => $venda->valor_total,
            'debtor_name' => $venda->name,
            'email' => $venda->email,
            'debtor_document_number' => $venda->cpf,
            'phone' => $venda->telefone,
            'method_pay' => 'pix',
            'postback' => 'web',
            'user' => $user
        ];

        if (!is_null($user->webhook_url) && in_array('gerado', (array) $user->webhook_endpoint)) {
            Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->post($user->webhook_url, [
                    'nome' => $venda->name,
                    'cpf' => preg_replace('/\D/', '', $venda->cpf),
                    'telefone' => preg_replace('/\D/', '', $venda->telefone),
                    'email' => $venda->email,
                    'status' => 'pendente'
                ]);
        }

        $request = new Request($dataRequest);
        $response = null;

        switch($user->gateway_cashin) {
            case 'cashtime':
                $response = CashtimeTrait::requestDepositCashtime($request);
                break;
            case 'jupiterpay':
                $response = JupiterPayTrait::requestDepositJupiterpay($request);
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Gateway de pagamento não suportado.']);
        }
		
        $status = isset($response['status']) && $response['status'] == 200 ? 'success' : 'error';
        if ($status == "success") {
            $cahsout = Solicitacoes::where('idTransaction', $response['data']['idTransaction'])->first();
            $cahsout->update(['descricao_transacao' => 'PRODUTO']);

            $venda->idTransaction = $response['data']['idTransaction'];
            $venda->qrcode = $response['data']['qrcode'];
            $venda->save();
            $valor_text = "R$ " . number_format($venda->valor_total, '2', ',', '.');
            return response()->json(["status" => $status, "data" => $response['data'], "valor_text" => $valor_text]);
        } else {
            return response()->json(['status' => 'error', 'message' => "Verifique e tente novamente."]);
        }
    }

    public function statusPedido(Request $request)
    {
        $data = $request->except(['/checkout/cliente/pedido/status']);
        $order = CheckoutOrders::where('idTransaction', $data['idTransaction'])->first();

        $status = $order->status;
        $message = "Aguardando pagamento...";
        if ($status == 'pago') {
            $message = "Pagamento realizado com sucesso!";
        }
        return response()->json(compact('status', 'message'));
        //dd($data, $order);
    }

    public function salvarDepoimento(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'depoimento' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'avatar' => 'nullable|string',
            'id' => 'nullable|string',
            'checkout_id' => 'required'
        ]);

        $depoimento = [
            'id' => $validated['id'],
            'nome' => $validated['nome'],
            'depoimento' => $validated['depoimento'],
            'avatar' => $validated['avatar'] ?? null,
            'checkout_id' => $validated['checkout_id'],
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'dep_' . $depoimento['id'] . '.' . $file->getClientOriginalExtension();
            $path = "checkouts/{$depoimento['id']}/";
            $file->move(public_path($path), $filename);
            $depoimento['avatar'] = '/' . $path . $filename;
        }
        //dd($depoimento);
        // Aqui você pode salvar em banco se quiser
        if (is_null($depoimento['id'])) {
            unset($depoimento['id']);
            $depoimento = DB::table('checkout_depoimentos')->insert($depoimento);
        } else {
            DB::table('checkout_depoimentos')->update(['id' => $depoimento['id']], $depoimento);
        }


        return response()->json([
            'success' => true,
            'depoimento' => $depoimento
        ]);
    }


    public function removerDepoimento(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID não informado.'], 400);
        }

        $depoimento = CheckoutDepoimento::find($id);

        if (!$depoimento) {
            return response()->json(['success' => false, 'message' => 'Depoimento não encontrado.'], 404);
        }

        try {
            $depoimento->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao remover depoimento.']);
        }
    }
}
