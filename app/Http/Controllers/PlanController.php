<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    // Lista todos os planos disponíveis
    public function index()
    {
        $plans = Plan::where('active', true)->with('features')->get();
        $user = Auth::user();
        $activeSubscription = $user->planSubscriptions()->whereNull('date_end')->latest()->first();
        $activePlanSlug = optional(optional(auth()->user()->activePlan)->plan)->slug ?? 'basico';

        $request = request();
        $ver = $request->segment(1);
        $viewName = $ver === 'v2' ? 'dashboard-v2.profile.planos' : 'profile.planos';

        return view($viewName, compact('plans', 'activeSubscription', 'activePlanSlug'));
    }

    // Assina um plano
    public function subscribe(Request $request, $planId)
    {
        $user = Auth::user();
        $plan = Plan::findOrFail($planId);

        // Encerra assinatura ativa anterior, se houver
        $user->planSubscriptions()->whereNull('date_end')->update(['date_end' => now()]);

        // Cria nova assinatura
        PlanSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'amount' => $plan->amount,
            'date_start' => now(),
            // 'date_end' => null, // default
        ]);

        return redirect()->back()->with('success', 'Plano assinado com sucesso!');
    }

    // Histórico de assinaturas do usuário
    public function history()
    {
        $subscriptions = Auth::user()->planSubscriptions()->with('plan')->orderByDesc('date_start')->get();
        return view('profile.planos_historico', compact('subscriptions'));
    }

    // Cancela assinatura ativa (opcional)
    public function cancel()
    {
        $user = Auth::user();
        $active = $user->planSubscriptions()->whereNull('date_end')->latest()->first();
        if ($active) {
            $active->update(['date_end' => now()]);
        }
        return redirect()->back()->with('success', 'Assinatura cancelada!');
    }
}