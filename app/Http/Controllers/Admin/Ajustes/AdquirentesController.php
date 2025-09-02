<?php

namespace App\Http\Controllers\Admin\Ajustes;

use App\Http\Controllers\Controller;
use App\Models\Adquirente;
use Illuminate\Http\Request;
use App\Models\Cashtime;
use App\Models\App;

class AdquirentesController extends Controller
{
    public function index()
    {
        $cashtime = Cashtime::first();
        $settings = App::first();
        $adquirentes = Adquirente::all();

        return view("admin.ajustes.adquirentes", compact(
            'cashtime',
            'settings',
            'adquirentes'
        ));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        $payload = [];
        foreach ($data as $key => $value) {
            if($key == 'secret'){
                $payload[$key] = $value;
            } else {
                $payload[$key] = (float) $value;
            }
        }
        //dd($request->all());
        $setting = Cashtime::first()->update($payload);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }

    public function setDefault(Request $request)
    {
        $request->validate([
            'adquirente_deposito' => 'required|string',
            'adquirente_saque' => 'required|string',
        ]);

        $data = [
            'gateway_cashin_default' => $request->adquirente_deposito,
            'gateway_cashout_default' => $request->adquirente_saque,
        ];

        App::first()->update($data);
        return back()->with('success', 'Gateway de pagamento definido como padrão com sucesso!');
    }
}
