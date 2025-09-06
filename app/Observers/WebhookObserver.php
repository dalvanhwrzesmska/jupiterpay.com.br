<?php

namespace App\Observers;

use App\Models\Solicitacoes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class WebhookObserver
{

    public function handlerWebhook(Solicitacoes $solicitacao)
    {
        $user = User::where('user_id', $solicitacao->user_id)->firstOrFail();
        if(!$user) {
            return;
        }

        $status = $solicitacao->status;

        switch ($status) {
            case 'PAID_OUT':
                $status = 'pago';
                break;
            case 'CANCELLED':
                $status = 'cancelado';
                break;
            case 'EXPIRED':
                $status = 'expirado';
                break;
            case 'REFUNDED':
                $status = 'estornado';
                break;
            case 'WAITING_FOR_APPROVAL':
                $status = 'gerado';
                break;
            default:
                $status = 'desconhecido';
                break;
        }

        // Garantir que webhook_endpoint seja um array
        $webhookEndpoints = $user->webhook_endpoint;
        if (!is_array($webhookEndpoints)) {
            $webhookEndpoints = is_string($webhookEndpoints) ? json_decode($webhookEndpoints, true) : [];
        }
        
        if (isset($user->webhook_url) && !is_null($user->webhook_url) && 
            in_array($status, $webhookEndpoints)) {
            Log::debug("Enviando webhook para " . $user->webhook_url);
            $response = Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->post($user->webhook_url, [
                    'nome' => $solicitacao->client_name,
                    'cpf' => preg_replace('/\D/', '', $solicitacao->client_document),
                    'telefone' => preg_replace('/\D/', '', $solicitacao->client_telefone),
                    'email' => $solicitacao->client_email,
                    'status' => $status
            ]);
        }
    }

    public function created(Solicitacoes $solicitacao)
    {
        $this->handlerWebhook($solicitacao);
    }

    public function updated(Solicitacoes $solicitacao)
    {
        $this->handlerWebhook($solicitacao);
    }

    public function deleted(Solicitacoes $solicitacao)
    {
        // Lógica a ser executada quando um depósito for excluído
    }
}
