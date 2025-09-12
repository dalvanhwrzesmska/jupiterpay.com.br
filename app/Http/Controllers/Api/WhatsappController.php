<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class WhatsappController extends Controller
{
    public function sendMessage($phone, $status)
    {
        if(empty($phone)) {
            return;
        }

        //54996757906 = 11
        //5554996757906 = 13
        $phone = preg_replace('/\D/', '', $phone);
        if(strlen($phone) == 11 && substr($phone,0,2) != '55'){
            $phone = "55{$phone}";
        }

        if(strlen($phone) == 13 && $status == 'conta_aprovada'){
            Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://n8n.freelacode.com.br/webhook/jupiterpay-cadastro-aprovado', [
                'phone' => $phone,
                'status' => 'conta_aprovada',
            ]);
        }
    }
}