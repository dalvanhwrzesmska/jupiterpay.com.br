@php
$setting = \App\Helpers\Helper::getSetting();
\App\Helpers\Helper::calculaSaldoLiquido(auth()->user()->user_id);
@endphp
<style>
    #areaChart {
        width: 100%;
        min-height: 350px;
    }
</style>
<x-app-layout :route="'Dashboard'">
    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Atenção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Você precisa concluir o cadastro para ativar sua conta.
                </div>
                <div class="modal-footer">
                    <a href="{{ url('/enviar-doc') }}" class="btn btn-success">Enviar Documentos</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content app-content">
        <div class="container-fluid">


            @if($status == 0)
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="p-3 d-grid border-bottom border-block-end-dashed">
                            <h5 class="card-title">Ativação de Conta</h5>
                            <p class="card-text">Para ativar sua conta, é necessário o envio de documentos. Por favor, envie os documentos para análise.</p>
                            <a href="{{ url('/enviar-doc') }}" class="btn btn-success">Enviar Documentos</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($status == 5)
            <div class="p-5 container-xl">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card card-raised">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <h5 class="card-title">Sua conta está em Análise</h5>
                                <p class="card-text">Nossa equipe está analisando seus documentos e logo vai entrar em contato.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($status == 1)
            <div class="container">
                @if(isset($setting->gateway_banner_home) && !is_null($setting->gateway_banner_home))
                <div class="mt-5 mb-1 row">
                    <img src="{{ $setting->gateway_banner_home }}" width="1280" height="126">
                </div>
                @endif
                <div class="mb-3 row justify-content-between align-items-">
                    <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                        <h1 class="mb-0 display-5">Dashboard</h1>
                    </div>
                    <form class="mt-3 col-12 col-md-8" method="GET" action="{{ route('dashboard') }}" id="filtroForm">
                        <div class="gap-3 d-flex flex-column flex-md-row">
                            <div class="mb-3 form-outlined-select position-relative w-100 w-md-50 mb-md-0">
                                <select id="produtoSelect" class="form-select" name="produto"  onchange="document.getElementById('filtroForm').submit()" required>
                                    <option value="todos" {{ request('periodo') == 'todos' ? 'selected' : '' }}>Todos</option>
                                    @foreach(auth()->user()->produtos as $produto)
                                        <option value="{{ $produto->id }}" {{ request('produto') == $produto->id ? 'selected' : '' }}>{{ $produto->produto_name }}</option>
                                    @endforeach
                                </select>
                                <label for="produtoSelect">Produto</label>
                            </div>
                            <div class="form-outlined-select position-relative w-100 w-md-50">
                                <select id="periodoSelect" class="form-select" name="periodo" onchange="submitPeriod()" required>
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
                                <label for="periodoSelect">Período</label>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">R$ {{ number_format(auth()->user()->saldo ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Saldo disponível</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="text-lg fa-solid fa-dollar"></i></div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class=" fa-solid fa-clock text-warning"></i>&nbsp;
                                    <div class=" caption text-warning fw-500 me-2">Pendente:</div>
                                    <div class="caption text-warning"> R$ {{number_format(auth()->user()->valor_saque_pendente, '2',',','.')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">R$ {{ number_format((clone $solicitacoes)->where('status', 'PAID_OUT')->sum('amount') ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Vendas Realizadas</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-lg fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ (clone $solicitacoes)->where('status', 'PAID_OUT')->count() }}</div>
                                    <div class="card-text">Quantidade de vendas</div>
                                </div>
                                <div class="text-white icon-circle bg-secondary card-color"><i class="fa-solid fa-arrow-down-short-wide"></i></i></div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    @php
                                        $paidOutSolicitacoes = (clone $solicitacoes)->where('status', 'PAID_OUT');
                                        $countPaidOut = $paidOutSolicitacoes->count();
                                        $ticketMedio = $countPaidOut > 0 ? $paidOutSolicitacoes->sum('amount') / $countPaidOut : 0;
                                    @endphp

                                    <div class="display-5">
                                        R$ {{ number_format($ticketMedio, 2, ',', '.') }}
                                    </div>
                                    <div class="card-text">Ticket médio</div>
                                </div>

                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid fa-arrows-rotate"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start:: row-1 -->
            <div class="row ">
                <!-- End:: row-1 -->
                <div class="mb-4 col-lg-8">
                    <div class="card card-raised h-100">
                        <div class="p-4 card-body">
                            <div class="row gx-4">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatablesDash" class="table text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Meios de Pagamento</th>
                                                    <th scope="col">Aprovação</th>
                                                    <th scope="col">Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="gap-3">
                                                            <i class="text-lg fa-brands fa-pix color-gateway"></i>
                                                            <span class="text-lg">Pix</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>
                                                            @php
                                                                $totalFiltradas = (clone $solicitacoes)->count();
                                                                $totalAprovadas = (clone $solicitacoes)->where('status', 'PAID_OUT')->count();
                                                                $porcentagemAprovadas = 0;

                                                                if ($totalFiltradas > 0) {
                                                                    $porcentagemAprovadas = ($totalAprovadas / $totalFiltradas) * 100;
                                                                }
                                                            @endphp
                                                            {{ number_format($porcentagemAprovadas, 2, ',', '.') }}%
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p>R$ {{ number_format((clone $solicitacoes)->where('status', 'PAID_OUT')->sum('amount') ?? 0, 2, ',', '.') }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="gap-3">
                                                            <i class="text-lg fa-solid fa-barcode color-gateway"></i>
                                                            <span class="text-lg">Boleto</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>0.00%</p>
                                                    </td>
                                                    <td>
                                                        <p>R$ 0,00</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="gap-3">
                                                            <i class="text-lg fa-solid fa-credit-card color-gateway"></i>
                                                            <span class="text-lg">Cartão</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>0.00%</p>
                                                    </td>
                                                    <td>
                                                        <p>R$ 0,00</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-lg-4">
                    <div class="card card-raised h-100">
                        <div class="p-4 card-body">
                            <div class="d-flex h-100 w-100 align-items-center justify-content-center">
                                <ul class="border-r-8 border-none list-group list-group-flush w-100">
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="mb-2 col-12">
                                                <h6 >Abandono C.</h6>
                                            </div>
                                            <div class="flex justify-between col-12 align-center">
                                                <p id="label-abandono" class="text-xl font-bold color-gateway">0%</p>
                                                <div><i onclick="setHidden('vis-abandono', 'label-abandono','0%')" id="vis-abandono" class="text-xl cursor-pointer fa-solid fa-eye color-gateway"></i></div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="mb-2 col-12">
                                                <h6 >Reembolso</h6>
                                            </div>
                                            <div class="flex justify-between col-12 align-center">
                                                <p id="label-reembolso" class="text-xl font-bold color-gateway">0%</p>
                                                <div><i onclick="setHidden('vis-reembolso', 'label-reembolso','0%')" id="vis-reembolso" class="text-xl cursor-pointer fa-solid fa-eye color-gateway"></i></div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="mb-2 col-12">
                                                <h6 >Charge Back</h6>
                                            </div>
                                            <div class="flex justify-between col-12 align-center">
                                                <p id="label-chargeback" class="text-xl font-bold color-gateway">0%</p>
                                                <div><i onclick="setHidden('vis-chargeback', 'label-chargeback','0%')" id="vis-chargeback" class="text-xl cursor-pointer fa-solid fa-eye color-gateway"></i></div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="mb-2 col-12">
                                                <h6 >MED</h6>
                                            </div>
                                            <div class="flex justify-between col-12 align-center">
                                                <p id="label-med" class="text-xl font-bold color-gateway">0%</p>
                                                <div><i onclick="setHidden('vis-med', 'label-med','0%')" id="vis-med" class="text-xl cursor-pointer fa-solid fa-eye color-gateway"></i></div>
                                            </div>
                                        </div>
                                    </li>
                                  </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" row" >
                <div class="mb-3 col-md-8">
                    <div class="overflow-hidden card card-raised">
                        <div class="bg-white card-header text-slate-300">
                            <div class="card-title text-slate-300">Estatísticas de vendas</div>
                        </div>
                        <div class="p-5 card-body ">
                            <div id="areaChart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="overflow-hidden card card-raised">
                        <div class="bg-white card-header text-slate-300">
                            <div class="card-title text-slate-300">Ultimas de vendas</div>
                        </div>
                        <div class="px-3 pb-7 card-body ">
                            <section class="py-1">
                                <div class="timeline">
                                    @foreach($ultimasTransacoes as $row)
                                        @php
                                            $isPayment = isset($row->beneficiaryname);
                                            $data = isset($row->date) ? \Carbon\Carbon::parse($row->date) : \Carbon\Carbon::parse($row->date);
                                            $valor = isset($row->amount) ? $row->amount : $row->cash_out_liquido;
                                        @endphp

                                        <div class="timeline-item">
                                            <div class="timeline-date">{{ $data->format('d/m/Y \à\s H:i:s') }}</div>
                                            @if($isPayment)
                                                <div class="fw-semibold text-warning">Pagamento realizado</div>
                                                <div class="amount-credit text-warning">- R$ {{ number_format($valor, 2, ',', '.') }}</div>
                                            @else
                                                <div class="fw-semibold text-success">Pagamento recebido</div>
                                                <div class="amount-credit text-success">+ R$ {{ number_format($valor, 2, ',', '.') }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            @endif
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


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const solicitacoes = {!! json_encode((clone $solicitacoesPaid)->where('status', 'PAID_OUT')->get()) !!};

    if (!Array.isArray(solicitacoes) || solicitacoes.length === 0) {
        console.warn("ℹ️ Nenhuma solicitação encontrada. O gráfico será renderizado vazio.");
    }

    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();
    const day = now.getDate();

    const labels = [];
    const depositMap = {};

    for (let h = 0; h < 24; h++) {
        const hourLabel = `${h.toString().padStart(2, '0')}:00`;
        labels.push(hourLabel);
        depositMap[hourLabel] = 0;
    }

    // Preenche o mapa de depósitos com os valores reais
    solicitacoes.forEach(item => {
     
        if (!item.date || !item.amount) return;

        const date = new Date(item.date);
 
        

        const hourLabel = `${date.getHours().toString().padStart(2, '0')}:00`;
        const amount = parseFloat(item?.amount) || 0;

        if (item.status === 'PAID_OUT') {
            depositMap[hourLabel] += amount;
        }
    });

    const seriesDeposit = labels.map(h => depositMap[h]);

    const options = {
        series: [
            { name: 'Depósitos', data: seriesDeposit }
        ],
        chart: {
            height: 350,
            type: 'area',
            zoom: { enabled: false },
            toolbar: { show: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        legend: { show: false },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        xaxis: {
            categories: labels,
            labels: { rotate: 0 }
        },
        tooltip: {
            x: { show: true }
        },
        colors: ["{{ $setting->gateway_color ?? '#7367F0' }}"],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0,
                stops: [0, 100]
            }
        }
    };

    const chartElement = document.querySelector("#areaChart");

    if (!chartElement) {
        console.error("❌ Erro: elemento #areaChart não encontrado no DOM.");
        return;
    }

    // Destroi instância anterior se existir
    if (window.areaChartInstance) {
        window.areaChartInstance.destroy();
    }

    // Cria nova instância e armazena globalmente
    window.areaChartInstance = new ApexCharts(chartElement, options);
    window.areaChartInstance.render();

    // Garante que o gráfico seja redesenhado ao redimensionar
    window.addEventListener("resize", () => {
        if (window.areaChartInstance) {
            window.areaChartInstance.render();
        }
    });
});
</script>

<script>
function setHidden(buttonId, labelId, value) {
    const btn = document.getElementById(buttonId);
    const lbl = document.getElementById(labelId);

    const hiddenKey = `hidden-${buttonId}`;

    if (btn.classList.contains('fa-eye')) {
        btn.classList.remove('fa-eye');
        btn.classList.add('fa-eye-slash');
        lbl.innerText = '---';
        localStorage.setItem(hiddenKey, 'true');
    } else {
        btn.classList.remove('fa-eye-slash');
        btn.classList.add('fa-eye');
        lbl.innerText = value;
        localStorage.setItem(hiddenKey, 'false');
    }
}

function restoreVisibility(buttonId, labelId, value) {
    const btn = document.getElementById(buttonId);
    const lbl = document.getElementById(labelId);
    const hiddenKey = `hidden-${buttonId}`;

    const isHidden = localStorage.getItem(hiddenKey) === 'true';

    if (isHidden) {
        btn.classList.remove('fa-eye');
        btn.classList.add('fa-eye-slash');
        lbl.innerText = '---';
    } else {
        btn.classList.remove('fa-eye-slash');
        btn.classList.add('fa-eye');
        lbl.innerText = value;
    }
}

document.addEventListener('DOMContentLoaded', function () {
        restoreVisibility('vis-abandono', 'label-abandono', '0%');
        restoreVisibility('vis-reembolso', 'label-reembolso', '0%');
        restoreVisibility('vis-chargeback', 'label-chargeback', '0%');
        restoreVisibility('vis-med', 'label-med', '0%');
    });

</script>
</x-app-layout>
