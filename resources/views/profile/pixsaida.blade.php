<style>
.pixsaida-page-card-raised {
    border-radius: 17px !important;
    box-shadow: 0 5px 24px 0 rgba(60,80,180,0.11), 0 1.5px 3px 0 rgba(0,0,0,0.07);
    border: none;
    background: #fff;
    border-bottom: 3px solid #e3e8ee;
}
.pixsaida-page-highlight-card {
    background: #fff;
}
.pixsaida-page-icon-circle {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 6px 0 rgba(60,140,231,0.12);
    font-size: 1.5rem;
}
.pixsaida-page-table-wrapper {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px 0 rgba(60,80,180,0.08), 0 1.5px 3px 0 rgba(0,0,0,0.04);
    overflow: hidden;
    margin-bottom: 2rem;
}
.pixsaida-page-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.97rem;
    background: #fff;
}
.pixsaida-page-table thead tr {
    background: #f6f8fa;
    box-shadow: 0 2px 8px 0 rgba(60,80,180,0.06);
}
.pixsaida-page-table thead th {
    color: #3c4a5a;
    font-weight: 500;
    padding: 0.85rem 0.7rem;
    border-bottom: 1.5px solid #e3e8ee;
    font-size: 0.99rem;
    letter-spacing: 0.01em;
    background: #f6f8fa;
}
.pixsaida-page-table tbody tr {
    transition: background 0.18s;
}
.pixsaida-page-table tbody tr:nth-child(even) {
    background: #f9fafb;
}
.pixsaida-page-table tbody tr:hover {
    background: #f1f5fd;
}
.pixsaida-page-table td {
    padding: 0.75rem 0.7rem;
    border: none;
    vertical-align: middle;
    color: #222;
}
.pixsaida-page-table th:first-child, .pixsaida-page-table td:first-child {
    border-top-left-radius: 14px;
}
.pixsaida-page-table th:last-child, .pixsaida-page-table td:last-child {
    border-top-right-radius: 14px;
}
.pixsaida-page-badge {
    font-size: 0.93rem;
    border-radius: 1.2rem;
    padding: 0.38em 1em;
    font-weight: 600;
    letter-spacing: 0.02em;
    border: none;
    box-shadow: none;
    display: inline-block;
}
.pixsaida-page-badge.aprovado {
    background: #e6f7ee;
    color: #1a7f37;
}
.pixsaida-page-badge.pendente {
    background: #fff7e6;
    color: #b26a00;
}
.pixsaida-page-badge.cancelado {
    background: #ffeaea;
    color: #b20000;
}
.pixsaida-page-badge.desconhecido {
    background: #e3e8ee;
    color: #888;
}
@media (max-width: 900px) {
    .pixsaida-page-table th, .pixsaida-page-table td { font-size: 0.93rem; padding: 0.6rem 0.3rem; }
}
</style>
<x-app-layout :route="'Relatório de saídas'">
    <div class="main-content app-content">
        <div class="container-fluid pixsaida-page">
            <!-- Start::page-header -->
            <div class="mb-3 md-mb-0 row">
                <div class="col-12 col-lg-6 mb-2 mb-lg-0">
                    <h1 class="fw-bold text-dark mb-0">Saídas</h1>
                </div>
                <div class="col-12 col-lg-6">
                    <form method="GET" action="{{ route('profile.relatorio.pixsaida') }}" id="filtroForm">
                        <div class="row gx-2 gy-2 align-items-center">
                            <div class="col-12 col-md-7">
                                <input type="search" class="form-control form-control-lg rounded-pill px-4" id="buscar" name="buscar" placeholder="Buscar por nome, chave ou documento" value="{{ request('buscar') }}" autofocus>
                            </div>
                            <div class="col-8 col-md-4">
                                <select class="form-select form-select-lg rounded-pill px-3" id="periodoSelect" name="periodo" required>
                                    <option value="hoje" {{ request('periodo') == 'hoje' ? 'selected' : '' }}>Hoje</option>
                                    <option value="ontem" {{ request('periodo') == 'ontem' ? 'selected' : '' }}>Ontem</option>
                                    <option value="7dias" {{ request('periodo') == '7dias' ? 'selected' : '' }}>Último 7 dias</option>
                                    <option value="30dias" {{ request('periodo') == '30dias' ? 'selected' : '' }}>Último 30 dias</option>
                                    <option value="tudo" {{ request('periodo') == 'tudo' ? 'selected' : '' }}>Sempre</option>
                                    <option value="personalizado">Personalizado</option>
                                </select>
                            </div>
                            <div class="col-4 col-md-1 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary rounded-circle shadow-sm" style="width:44px;height:44px;"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Cards resumo -->
            <div class="container-fluid px-2 mb-4">
                <div class="row g-4">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card card-raised highlight-card h-100">
                            <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="display-6 fw-bold text-success">{{ (clone $transactions)->count() }}</div>
                                    <div class="text-muted">Transações</div>
                                </div>
                                <div class="icon-circle bg-info text-white"><i class="fa-solid fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card card-raised highlight-card h-100">
                            <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="display-6 fw-bold text-primary">R$ {{ number_format((clone $transactions)->sum('amount'), '2',',','.') }}</div>
                                    <div class="text-muted">Saídas</div>
                                </div>
                                <div class="icon-circle bg-primary text-white"><i class="fa-solid fa-arrow-up-short-wide"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card card-raised highlight-card h-100">
                            <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="display-6 fw-bold text-info">R$ {{ number_format((clone $transactions)->sum('cash_out_liquido'), '2',',','.') }}</div>
                                    <div class="text-muted">Chargebacks</div>
                                </div>
                                <div class="icon-circle bg-info text-white"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card card-raised highlight-card h-100">
                            <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between">
                                <div>
                                    @php
                                    $ticketMedio = 0;
                                    $totalTransacoesFiltradas = (clone $transactions)->count();
                                    if ($totalTransacoesFiltradas > 0) {
                                        $somaSaqueLiquido = (clone $transactions)->sum('amount');
                                        $ticketMedio = $somaSaqueLiquido / $totalTransacoesFiltradas;
                                    }
                                    @endphp
                                    <div class="display-6 fw-bold text-secondary">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</div>
                                    <div class="text-muted">MED</div>
                                </div>
                                <div class="icon-circle bg-secondary text-white"><i class="fa-solid fa-ban"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabela -->
            <div class="row px-2">
                <div class="col-12">
                    <div class="pixsaida-page-table-wrapper">
                        <div class="table-responsive p-0">
                            <table class="pixsaida-page-table">
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
                                        <td>{{ "R$ ".number_format($transaction->amount, '2',',','.') }}</td>
                                        <td>{{ "R$ ".number_format($transaction->cash_out_liquido, '2',',','.') }}</td>
                                        <td>{{ $transaction->beneficiaryname }}</td>
                                        <td>{{ $transaction->pix }}</td>
                                        <td>{{ $transaction->pixkey }}</td>
                                        <td>
                                            @switch($transaction->status)
                                                @case('COMPLETED')
                                                    <span class="pixsaida-page-badge aprovado">Aprovado</span>
                                                    @break
                                                @case('PENDING')
                                                    <span class="pixsaida-page-badge pendente">Pendente</span>
                                                    @break
                                                @case('CANCELLED')
                                                    <span class="pixsaida-page-badge cancelado">Cancelado</span>
                                                    @break
                                                @default
                                                    <span class="pixsaida-page-badge desconhecido">Desconhecido</span>
                                            @endswitch
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                        <td>
                                            R$ {{ number_format((float)$transaction->amount - (float)$transaction->cash_out_liquido, '2', ',', '.') }}
                                        </td>
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
            <!-- Modal e scripts permanecem -->
    <!-- Modal -->
<div class="modal fade" id="dateRangeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="border-0 modal-content rounded-4">
        <div class="justify-center p-4 pl-5 modal-body align-center">
          <h5 class="mb-4 fw-semibold">Selecione o período</h5>

          <div class="row">
            <div class="mb-3 text-center col-md-6">
              <strong class="mb-2 d-block">Data de Início</strong>
              <div class="d-flex justify-content-center" id="calendarInicio"></div>
            </div>
            <div class="text-center col-md-6">
              <strong class="mb-2 d-block">Data de Fim</strong>
              <div class="d-flex justify-content-center" id="calendarFim"></div>
            </div>
          </div>
        </div>
        <div class="gap-2 mt-4 modal-footer d-flex justify-content-end">
            <button class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button>
            <button class="btn btn-success" id="btnAplicarDatas">Aplicar</button>
        </div>
      </div>
    </div>
  </div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
   const select = document.getElementById("periodoSelect");
   const buscar = document.getElementById("buscar");
   const form = document.getElementById("filtroForm");
   const modalEl = document.getElementById('dateRangeModal');
   const btnAplicar = document.getElementById("btnAplicarDatas");

   let dataInicioSelecionada = null;
   let dataFimSelecionada = null;

   function formatarDataBr(data) {
     const meses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
     const dia = String(data.getDate()).padStart(2, '0');
     const mes = meses[data.getMonth()];
     return `${dia} ${mes}`;
   }

   // Flatpickrs
   flatpickr("#calendarInicio", {
     inline: true,
     locale: "pt",
     dateFormat: "Y-m-d",
     onChange: function (selectedDates) {
       dataInicioSelecionada = selectedDates[0];
     }
   });

   flatpickr("#calendarFim", {
     inline: true,
     locale: "pt",
     dateFormat: "Y-m-d",
     onChange: function (selectedDates) {
       dataFimSelecionada = selectedDates[0];
     }
   });

   // Abrir modal
   select.addEventListener("change", function () {
     if (select.value === "personalizado") {
       new bootstrap.Modal(modalEl).show();
     } else {
       form.submit();
     }
   });

   buscar.addEventListener('input', function () {
    setTimeout(() => {
        form.submit();
    }, 500);
   })

   // Aplicar datas
   btnAplicar.addEventListener("click", function () {
     if (dataInicioSelecionada && dataFimSelecionada) {
       const inicioStr = dataInicioSelecionada.toISOString().split("T")[0];
       const fimStr = dataFimSelecionada.toISOString().split("T")[0];
       const texto = `${formatarDataBr(dataInicioSelecionada)} – ${formatarDataBr(dataFimSelecionada)}`;
       const valor = `${inicioStr}:${fimStr}`;

       // Remover opção personalizada anterior, se existir
       let opExistente = select.querySelector('option[data-personalizado]');
       if (opExistente) select.removeChild(opExistente);

       // Criar nova opção personalizada
       let op = document.createElement("option");
       op.value = valor;
       op.textContent = texto;
       op.setAttribute("data-personalizado", "1");
       select.appendChild(op);
       select.value = valor; // Define como selecionado

       // Fechar modal e submeter
       bootstrap.Modal.getInstance(modalEl).hide();
       form.submit();
     } else {
       alert("Selecione ambas as datas.");
     }
   });

   // Restaurar valor da URL, se existir
   const urlParams = new URLSearchParams(window.location.search);
   const periodo = urlParams.get('periodo');

   if (periodo && select) {
     if (periodo.includes(':')) {
       const [inicioStr, fimStr] = periodo.split(':');
       const inicioDate = new Date(inicioStr);
       const fimDate = new Date(fimStr);
       dataInicioSelecionada = inicioDate;
       dataFimSelecionada = fimDate;

       const texto = `${formatarDataBr(inicioDate)} – ${formatarDataBr(fimDate)}`;
       let op = document.createElement("option");
       op.value = periodo;
       op.textContent = texto;
       op.setAttribute("data-personalizado", "1");

       select.appendChild(op);
       select.value = periodo; // Seleciona corretamente
     } else {
       const optionToSelect = Array.from(select.options).find(opt => opt.value === periodo);
       if (optionToSelect) {
         optionToSelect.selected = true;
       }
     }
   }
 });

   </script>
</x-app-layout>
