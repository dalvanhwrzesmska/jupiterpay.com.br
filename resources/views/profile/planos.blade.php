<x-app-layout :route="'Assinatura de Planos'">

    <style>
        .planos-cards-row {
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 2.5rem;
        }

        .plano-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 5px 24px 0 rgba(60, 80, 180, 0.10), 0 1.5px 3px 0 rgba(0, 0, 0, 0.04);
            border: none;
            max-width: 350px;
            min-width: 270px;
            flex: 1 1 300px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            padding: 2.2rem 1.7rem 1.7rem 1.7rem;
            position: relative;
            margin-bottom: 1.5rem;
        }

        .plano-card-destaque {
            border: 2.5px solid #3c8ce7;
            box-shadow: 0 8px 32px 0 rgba(60, 80, 180, 0.13);
        }

        .plano-titulo {
            font-size: 1.35rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .plano-faixa {
            font-size: 1.01rem;
            color: #3c8ce7;
            font-weight: 600;
            text-align: center;
            margin-bottom: 0.7rem;
        }

        .plano-preco {
            font-size: 2.1rem;
            font-weight: 700;
            color: #10b981;
            text-align: center;
            margin-bottom: 0.2rem;
        }

        .plano-taxa {
            font-size: 1.08rem;
            color: #1a7f37;
            text-align: center;
            margin-bottom: 0.7rem;
        }

        .plano-beneficios {
            margin: 1.1rem 0 1.2rem 0;
            padding-left: 0.2rem;
        }

        .plano-beneficios li {
            margin-bottom: 0.5rem;
            font-size: 1.01rem;
            color: #444;
            list-style: disc inside;
        }

        .plano-btn {
            border-radius: 1.5rem;
            font-size: 1.08rem;
            padding: 0.9rem 1.2rem;
            font-weight: 600;
            background: #3c8ce7;
            color: #fff;
            border: none;
            margin-top: auto;
            transition: background 0.15s;
            box-shadow: 0 2px 8px 0 rgba(60, 80, 180, 0.08);
            width: 100%;
            display: block;
        }

        .plano-btn:hover {
            background: #10b981;
        }

        .plano-btn[disabled], .plano-btn.disabled {
            background: #e3e8ee !important;
            color: #888 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
        }

        @media (max-width: 1100px) {
            .planos-cards-row {
                flex-direction: column;
                gap: 1.5rem;
                align-items: stretch;
            }

            .plano-card {
                max-width: 100%;
                min-width: 0;
            }
        }
    </style>
    <div class="container-fluid px-0 py-4">
        <h1 class="fw-bold text-dark mb-4 text-center">Assinatura de Planos</h1>
        <div class="planos-cards-row">
            <div class="plano-card">
              @if($activePlanSlug === 'basico')
                <span style="position:absolute;top:-18px;left:50%;transform:translateX(-50%);" class="badge bg-success px-4 py-2 fs-6">Ativo</span>
              @endif
                <div class="plano-titulo">Plano Básico</div>
                <div class="plano-faixa">Faturamento até R$ 10.000/mês</div>
                <div class="plano-preco">R$ 0 <span style="font-size:1.1rem;font-weight:400;">/mês</span></div>
                <div class="plano-taxa">Taxa por transação: <b>2,0%</b></div>
                <ul class="plano-beneficios">
                    <li>Sem mensalidade</li>
                    <li>Suporte padrão via e-mail</li>
                    <li>Relatórios básicos de vendas</li>
                    <li>Acesso a extrato de movimentações</li>
                    <li>Liberação de saldo padrão (D+30)</li>
                </ul>
                <form method="POST" action="{{ route('planos.subscribe', 1) }}">
                  @csrf
                  <button type="submit" class="plano-btn @if($activePlanSlug === 'basico') disabled @endif" @if($activePlanSlug === 'basico') disabled @endif>Assinar Plano Básico</button>
                </form>
            </div>
            <div class="plano-card plano-card-destaque">
                @if($activePlanSlug === 'pro')
                  <span style="position:absolute;top:-18px;left:50%;transform:translateX(-50%);" class="badge bg-success px-4 py-2 fs-6">Ativo</span>
                @endif
                <div class="plano-titulo">Plano Pro</div>
                <div class="plano-faixa">Faturamento de R$ 10.001 até R$ 50.000/mês</div>
                <div class="plano-preco">R$ 99 <span style="font-size:1.1rem;font-weight:400;">/mês</span></div>
                <div class="plano-taxa">Taxa por transação: <b>1,5%</b></div>
                <ul class="plano-beneficios">
                    <li>Todos os benefícios do Básico</li>
                    <li>Suporte prioritário por e-mail e chat</li>
                    <li>Relatórios avançados (vendas, chargebacks, taxas)</li>
                    <li>Dashboard em tempo real de vendas e recebimentos</li>
                    <li>Ferramentas de reconciliação financeira</li>
                    <li>Antecipação de recebíveis sob consulta</li>
                </ul>
                <form method="POST" action="{{ route('planos.subscribe', 2) }}">
                  @csrf
                  <button type="submit" class="plano-btn @if($activePlanSlug === 'pro') disabled @endif" @if($activePlanSlug === 'pro') disabled @endif>Assinar Plano Pro</button>
                </form>
            </div>
            <div class="plano-card">
                @if($activePlanSlug === 'premium')
                  <span style="position:absolute;top:-18px;left:50%;transform:translateX(-50%);" class="badge bg-success px-4 py-2 fs-6">Ativo</span>
                @endif
                <div class="plano-titulo">Plano Premium</div>
                <div class="plano-faixa">Faturamento de R$ 50.001 até R$ 100.000/mês</div>
                <div class="plano-preco">R$ 449,90 <span style="font-size:1.1rem;font-weight:400;">/mês</span></div>
                <div class="plano-taxa">Taxa por transação: <b>1,0%</b></div>
                <ul class="plano-beneficios">
                    <li>Todos os benefícios do Pro</li>
                    <li>Suporte com gerente dedicado</li>
                    <li>Antecipação de recebíveis gratuita</li>
                    <li>Integração com sistemas de ERP</li>
                    <li>API exclusiva para automação</li>
                    <li>Personalização de relatórios e exportação de dados</li>
                    <li>Consultoria para otimização de meios de pagamento</li>
                    <li>Migração assistida de sistemas legados</li>
                </ul>
                <form method="POST" action="{{ route('planos.subscribe', 3) }}">
                  @csrf
                  <button type="submit" class="plano-btn @if($activePlanSlug === 'premium') disabled @endif" @if($activePlanSlug === 'premium') disabled @endif>Assinar Plano Premium</button>
                </form>
            </div>
        </div>
        <div class="mt-4 mb-2 mx-auto" style="max-width:900px;">
            <div class="alert alert-info" style="border-radius:1.2rem;">
                <b>Observações Gerais:</b><br>
                <ul class="mb-0 mt-2">
                    <li>As taxas são aplicadas sobre o valor transacionado.</li>
                    <li>Planos superiores incluem todos os benefícios dos anteriores.</li>
                    <li>Planos anuais têm 15% de desconto na mensalidade.</li>
                    <li>Migração de plano automática conforme o crescimento do faturamento.</li>
                    <li>Não há cobrança de mensalidade para o Plano Básico.</li>
                    <li>Valores e benefícios podem ser ajustados conforme negociação corporativa.</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>