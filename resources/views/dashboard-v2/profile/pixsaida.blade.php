@extends('dashboard-v2.layout')

@section('content')
<div class="container-fluid px-0">
    <div class="row align-items-center mb-4 gy-2 gx-3 px-2">
        <div class="col-12 col-lg-8 mb-2 mb-lg-0">
            <h2 class="fw-bold text-dark mb-0">Saídas PIX</h2>
            <p class="text-muted mb-0">Acompanhe suas saídas PIX e visualize os detalhes das transações realizadas.</p>
        </div>
        <div class="col-12 col-lg-4">
            <form class="d-flex gap-2 align-items-center justify-content-end" method="GET" action="{{ route('profile.relatorio.pixsaida') }}" id="filtroForm">
                <input class="form-control form-control-sm rounded-pill px-3 w-auto" type="search" placeholder="Buscar por nome, chave ou documento" aria-label="Buscar" name="buscar" value="{{ request('buscar') }}" style="max-width:180px;">
                <select class="form-select form-select-sm rounded-pill px-3 w-auto" id="periodoSelect" name="periodo" required style="max-width:140px;">
                    <option value="hoje" {{ request('periodo') == 'hoje' ? 'selected' : '' }}>Hoje</option>
                    <option value="ontem" {{ request('periodo') == 'ontem' ? 'selected' : '' }}>Ontem</option>
                    <option value="7dias" {{ request('periodo') == '7dias' ? 'selected' : '' }}>Últimos 7 dias</option>
                    <option value="30dias" {{ request('periodo') == '30dias' ? 'selected' : '' }}>Últimos 30 dias</option>
                    <option value="tudo" {{ request('periodo') == 'tudo' ? 'selected' : '' }}>Sempre</option>
                    <option value="personalizado">Personalizado</option>
                </select>
                <button type="submit" class="btn btn-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:36px;height:36px;"><span class="fa fa-search"></span></button>
            </form>
        </div>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-md-3">
            <div class="card overflow-hidden h-100">
                <div class="card-body position-relative">
                    <h6 class="mb-1">Transações</h6>
                    <div class="display-4 fs-5 mb-2 fw-normal font-sans-serif text-warning">{{ (clone $transactions)->count() }}</div>
                    <span class="fw-semi-bold fs-10 text-nowrap">R$ 0,00</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card overflow-hidden h-100">
                <div class="card-body position-relative">
                    <h6 class="mb-1">Saídas</h6>
                    <div class="display-4 fs-5 mb-2 fw-normal font-sans-serif text-info">R$ {{ number_format((clone $transactions)->sum('amount'), 2, ',', '.') }}</div>
                    <span class="fw-semi-bold fs-10 text-nowrap">R$ 0,00</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card overflow-hidden h-100">
                <div class="card-body position-relative">
                    <h6 class="mb-1">Chargebacks</h6>
                    <div class="display-4 fs-5 mb-2 fw-normal font-sans-serif text-info">R$ {{ number_format((clone $transactions)->sum('cash_out_liquido'), 2, ',', '.') }}</div>
                    <span class="fw-semi-bold fs-10 text-nowrap">R$ 0,00</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card overflow-hidden h-100">
                <div class="card-body position-relative">
                    @php
                        $ticketMedio = 0;
                        $totalTransacoesFiltradas = (clone $transactions)->count();
                        if ($totalTransacoesFiltradas > 0) {
                            $somaSaqueLiquido = (clone $transactions)->sum('cash_out_liquido');
                            $ticketMedio = $somaSaqueLiquido / $totalTransacoesFiltradas;
                        }
                    @endphp
                    <h6 class="mb-1">MED</h6>
                    <div class="display-4 fs-5 mb-2 fw-normal font-sans-serif">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</div>
                    <span class="fw-semi-bold fs-10 text-nowrap">R$ 0,00</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header bg-body-tertiary py-3">
            <h5 class="mb-0">Transações Realizadas</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                <table class="table table-hover table-striped overflow-hidden mb-0">
                    <thead>
                        <tr>
                            <th>Transação ID</th>
                            <th>Valor</th>
                            <th>Valor Líquido</th>
                            <th>Nome</th>
                            <th>Chave PIX</th>
                            <th>Tipo Chave</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Taxa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->idTransaction }}</td>
                            <td>{{ 'R$ '.number_format($transaction->amount, 2, ',', '.') }}</td>
                            <td>{{ 'R$ '.number_format($transaction->cash_out_liquido, 2, ',', '.') }}</td>
                            <td>{{ $transaction->beneficiaryname }}</td>
                            <td>{{ $transaction->pix }}</td>
                            <td>{{ $transaction->pixkey }}</td>
                            <td>
                                @switch($transaction->status)
                                    @case('COMPLETED')
                                        <span class="badge badge rounded-pill d-block p-2 badge-subtle-success">Aprovado<span class="ms-1 fas fa-check" data-fa-transform="shrink-2"></span></span>
                                        @break
                                    @case('PENDING')
                                        <span class="badge badge rounded-pill d-block p-2 badge-subtle-warning">Pendente<span class="ms-1 fas fa-stream" data-fa-transform="shrink-2"></span></span>
                                        @break
                                    @case('CANCELLED')
                                        <span class="badge badge rounded-pill d-block p-2 badge-subtle-danger">Cancelado<span class="ms-1 fas fa-ban" data-fa-transform="shrink-2"></span></span>
                                        @break
                                    @default
                                        <span class="badge badge rounded-pill d-block p-2 badge-subtle-secondary">Desconhecido</span>
                                @endswitch
                            </td>
                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y \à\s H:i:s') }}</td>
                            <td>R$ {{ number_format((float)$transaction->amount - (float)$transaction->cash_out_liquido, 2, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Nenhuma transação encontrada.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection