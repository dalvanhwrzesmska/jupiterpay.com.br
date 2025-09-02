<x-app-layout :route="'[ADMIN] Dashboard'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="mb-3 md-mb-0 row">
                <div class="mb-3 col md-mb-0 col-12 col-lg-10 text-start" >
                    <h1 class="display-5">Dashboard admin</h1>
                </div>

                <div class="col col-12 col-lg-2 text-end">
                    <form method="GET" action="{{ route('admin.dashboard') }}" id="filtroForm">
                        <div class="row g-2">

                            <div class="col col-12">
                                    <select id="periodoSelect" class="bg-transparent form-select" name="periodo" onchange="submitPeriod()" required>
                                        <option value="hoje" {{ request('periodo') == 'hoje' ? 'selected' : '' }}>Hoje</option>
                                        <option value="ontem" {{ request('periodo') == 'ontem' ? 'selected' : '' }}>Ontem</option>
                                        <option value="7dias" {{ request('periodo') == '7dias' ? 'selected' : '' }}>Último 7 dias</option>
                                        <option value="30dias" {{ request('periodo') == '30dias' ? 'selected' : '' }}>Último 30 dias</option>
                                        <option value="tudo" {{ request('periodo') == 'tudo' ? 'selected' : '' }}>Sempre</option>
                                        <option value="personalizado">Personalizado</option>
                                        {{-- @if(isset(request('periodo')) && str_contains(':', request('periodo')))
                                            <option value="{{ request('periodo') }}">{{ explode(':', request('periodo'))[0] - explode(':', request('periodo'))[1] }}</option>
                                        @endif --}}
                                    </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">R$ {{ number_format($carteiras, 2, ',', '.') }}</div>
                                    <div class="card-text md-text-xs">Saldo em carteiras</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="material-icons">account_balance_wallet</i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                 
                                    <div class="display-5 text-success">R$ {{ number_format($lucro_liquido ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Lucro Liquido</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-dollar-sign"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ (clone $solicitacoes)->where('status', 'PAID_OUT')->count() + (clone $saques)->where('status', 'COMPLETED')->count() }}</div>
                                    <div class="card-text">Transações aprovadas</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($valor_aprovado ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Transações aprovadas</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ $cadastros_total }}</div>
                                    <div class="card-text">Usuários cadastrados</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-people-group"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-warning">{{ $cadastros_analise }}</div>
                                    <div class="card-text">Usuários em análise</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">R$ {{ number_format((clone $saques)->sum('amount') ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Saques</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-arrow-up-short-wide"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">R$ {{ number_format($saques_pendentes->sum('amount') ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Saques pendentes</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-arrow-up-short-wide"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->

        </div>
    </div>

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
