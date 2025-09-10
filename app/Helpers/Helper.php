<?php

namespace App\Helpers;

use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\User;
use App\Models\App;
use App\Models\CheckoutBuild;
use App\Models\Nivel;
use App\Models\Transactions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Helper
{

    public static function calculaSaldoLiquido($user_id)
    {
        try {
            // Soma dos depósitos líquidos com status "PAID_OUT"
            $totalDepositoLiquido = Solicitacoes::where('user_id', $user_id)
                ->where('status', 'PAID_OUT')
                ->sum('deposito_liquido');

            // Soma dos saques aprovados com status "COMPLETED"
            $totalSaquesAprovados = SolicitacoesCashOut::where('user_id', $user_id)
                ->where('status', 'COMPLETED')
                ->sum('cash_out_liquido');

            $totalSaldoBloqueado = SolicitacoesCashOut::where('user_id', $user_id)
                ->where('status', 'PENDING')
                //->where('descricao_transacao', 'WEB')
                ->sum('cash_out_liquido');


            // Cálculo do saldo líquido
            $saldoLiquido = (float) $totalDepositoLiquido - (float) $totalSaquesAprovados - (float)$totalSaldoBloqueado;
            $gerente = User::where('id', $user_id)->first();
            if ($gerente) {
                $totalcomissoes = Transactions::where('gerente_id', $user_id)->sum('comission_value');
                $saldoLiquido += (float) $totalcomissoes;
            }
            // Atualizar o saldo do usuário
            $updated = User::where('user_id', $user_id)->update(['saldo' => $saldoLiquido, 'valor_saque_pendente' => $totalSaldoBloqueado]);

            return $updated ? true : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function calcularSaldoLiquidoUsuarios()
    {
        $users = User::get();
        foreach ($users as $user) {
            self::calculaSaldoLiquido($user->user_id);
        }
    }

    public static function generateValidCpf($pontuado = false)
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);

        // Calcula o primeiro dígito verificador
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - ($d1 % 11);
        $d1 = ($d1 >= 10) ? 0 : $d1;

        // Calcula o segundo dígito verificador
        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - ($d2 % 11);
        $d2 = ($d2 >= 10) ? 0 : $d2;

        if ($pontuado) {
            return sprintf(
                '%d%d%d.%d%d%d.%d%d%d-%d%d',
                $n1,
                $n2,
                $n3,
                $n4,
                $n5,
                $n6,
                $n7,
                $n8,
                $n9,
                $d1,
                $d2
            );
        } else {
            return sprintf(
                '%d%d%d%d%d%d%d%d%d%d%d',
                $n1,
                $n2,
                $n3,
                $n4,
                $n5,
                $n6,
                $n7,
                $n8,
                $n9,
                $d1,
                $d2
            );
        }
    }

    public static function getSetting()
    {
        return App::first();
    }

    public static function getNiveis()
    {
        return Nivel::get();
    }

    public static function meuNivel($user)
    {
        // Soma total dos depósitos pagos do usuário
        $depositos = Solicitacoes::where('status', 'PAID_OUT')
            ->where("user_id", $user->user_id)
            ->sum('amount');

        // Pega todos os níveis ordenados pelo mínimo
        $niveis = self::getNiveis()->sortBy('minimo')->values();

        $nivelAtual = null;
        $proximoNivel = null;

        foreach ($niveis as $index => $nivel) {
            if ($depositos >= $nivel->minimo && $depositos <= $nivel->maximo) {
                $nivelAtual = $nivel;
                $proximoNivel = $niveis->get($index + 1);
                break;
            }

            // Caso o usuário esteja acima de todos os níveis, assume o último como atual
            if ($index === $niveis->count() - 1 && $depositos > $nivel->maximo) {
                $nivelAtual = $nivel;
                $proximoNivel = null;
            }
        }

        return [
            'total_depositos' => $depositos,
            'nivel_atual' => $nivelAtual,
            'proximo_nivel' => $proximoNivel
        ];
    }


    public static function incrementAmount(User $user, $valor, $campo)
    {
        $usuario = $user->toArray();
        $novovalor = $usuario[$campo] + (float)$valor;
        $user->update([$campo => $novovalor]);
        $user->save();
    }

    public static function decrementAmount(User $user, $valor, $campo)
    {
        $usuario = $user->toArray();
        $novovalor = $usuario[$campo] - (float)$valor;
        $user->update([$campo => $novovalor]);
        $user->save();
    }

    public static function getPendingAprove()
    {
        return $totalSaldoBloqueado = SolicitacoesCashOut::where('status', 'PENDING')
            ->where('descricao_transacao', 'WEB')
            ->count();
    }

    public static function getProdutosPaid($userId)
    {
        $checkout = CheckoutBuild::where('user_id', $userId)->get();
        $orders = 0;
        foreach ($checkout as $check) {
            $orders += $check->orders->where('status', 'pago')->count();
        }
        return $orders;
    }

    public static function createTaxBalance($payload, $taxa)
    {
        $payload['idTransaction'] = $payload['idTransaction'] . '_TAX';
        $payload['externalreference'] = $payload['externalreference'] . '_TAX';
        $payload['type'] = 'TAX';
        $payload['amount'] = $taxa;
        $payload['beneficiaryname'] = 'Taxa de Saque';
        $payload['cash_out_liquido'] = $taxa;
        $payload['taxa_cash_out'] = $taxa;
        $payload['amount'] = 0;
        $payload['pix'] = '';
        $payload['pixkey'] = 'tax';
        SolicitacoesCashOut::create($payload);
    }

    public static function calcularTaxas($user_id, $valor, $operacao = 'cash_in')
    {
        $userModel = new User();
        $app = new App();
        $user = $userModel->where('user_id', $user_id)->first();

        if (!$user) {
            return false;
        }

        $appSettings = $app->first();
        $taxas_padrao = [
            'cash_in_padrao' => $appSettings->taxa_cash_in_padrao ?? 0, //porcentagem
            'cash_out_padrao' => $appSettings->taxa_cash_out_padrao ?? 0, //porcentagem
            'taxa_fixa_padrao' => $appSettings->taxa_fixa_padrao ?? 0, //valor fixo
            'taxa_fixa_padrao_cash_out' => $appSettings->taxa_fixa_padrao_cash_out ?? 0, //valor fixo
            'baseline' => $appSettings->baseline ?? 0, //valor fixo
        ];

        $taxas_usuario = [
            'cash_in_padrao' => $user->taxa_cash_in ?? $taxas_padrao['taxa_cash_in'],
            'cash_out_padrao' => $user->taxa_cash_out ?? $taxas_padrao['taxa_cash_out'],
            'taxa_fixa_padrao' => $user->taxa_cash_in_fixa ?? $taxas_padrao['taxa_cash_in_fixa'],
            'taxa_fixa_padrao_cash_out' => $user->taxa_cash_out_fixa ?? $taxas_padrao['taxa_cash_out_fixa'],
            'baseline' => $user->baseline ?? $taxas_padrao['baseline']
        ];

        if ($operacao === 'cash_out' && $user->baseline_cash_out > 0) {
            $taxas_usuario['baseline'] = $user->baseline_cash_out;
        }

        if ($operacao === 'cash_in' && $user->baseline_cash_in > 0) {
            $taxas_usuario['baseline'] = $user->baseline_cash_in;
        }

        $taxa_liquida = self::calcularTaxa(
            $valor,
            $taxas_usuario['taxa_fixa_padrao'],
            $taxas_usuario['cash_in_padrao'],
            $taxas_usuario['baseline']
        );

        $taxa_liquida_out = self::calcularTaxa(
            $valor,
            $taxas_usuario['taxa_fixa_padrao'],
            $taxas_usuario['cash_in_padrao'],
            $taxas_usuario['baseline']
        );

        $taxa_final = [
            'valor_bruto' => $valor,
            'cash_in_liquido' => $valor-$taxa_liquida,
            'cash_out_liquido' => $valor-$taxa_liquida_out,
            'cash_in_taxa' => $taxa_liquida,
            'cash_out_taxa' => $taxa_liquida_out,
            ...$taxas_usuario
        ];

        return $taxa_final;
    }

    /*
    @param $valor - valor total
    @param $taxaFixa - valor fixo a ser descontado
    @param $taxaPercentual - porcentagem a ser descontada
    */
    public static function calcularTaxa($valor, $taxaFixa = 0, $taxaPercentual = 0, $baseline = 0)
    {
        // Subtrai a taxa fixa primeiro
        $valorMenosFixa = $valor - $taxaFixa;
        if ($valorMenosFixa < 0) $valorMenosFixa = 0;

        // Calcula a taxa percentual sobre o valor restante
        $taxaPercentualCalculada = $valorMenosFixa * ($taxaPercentual / 100);

        // Soma ambas as taxas
        $somaTaxa = $taxaFixa + $taxaPercentualCalculada;

        // Aplica baseline se necessário
        if ($somaTaxa < $baseline && $baseline > 0) {
            $somaTaxa = $baseline;
        }

        return number_format($somaTaxa, 2, '.', '');
    }

    public static function salvarArquivo($request, $inputName, $pasta = 'uploads')
    {
        $messageError = [
            '*.mimes' => 'O arquivo deve ser do tipo: jpg, jpeg, png, gif, svg, webp.',
        ];

        $request->validate([
            $inputName => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ], $messageError);

        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Salvar o arquivo em storage/app/public/$pasta com nome personalizado
            $path = $file->storeAs("public/$pasta", $filename);

            if ($path) {
                return "/storage/$pasta/" . $filename;
            }
        }

        return null;
    }
}
