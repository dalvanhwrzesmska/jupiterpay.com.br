@php
$setting = \App\Helpers\Helper::getSetting();
\App\Helpers\Helper::calculaSaldoLiquido(auth()->user()->user_id);
@endphp

<style>
    /* Modern dashboard styling */
    .dashboard-gradient-bg {
        /*background: linear-gradient(135deg, #f8fafc 0%, #e8f0fe 100%);*/
        min-height: 100vh;
        padding-top: 20px;
    }

    .dashboard-header {
        border-radius: 18px;
        background: linear-gradient(90deg, #005bea 10%, #3c8ce7 90%);
        padding: 32px 32px;
        margin-bottom: 32px;
        color: #fff;
        box-shadow: 0 8px 32px 0 rgba(60,80,180,0.07), 0 1.5px 3px 0 rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 120px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-header h1 {
        font-size: 2.6rem;
        font-weight: 700;
        margin-bottom: 0;
        letter-spacing: -1px;
    }

    .dashboard-header img {
        border-radius: 15px;
        max-height: 80px;
        object-fit: cover;
        box-shadow: 0 4px 16px rgba(60, 120, 240, 0.08);
    }

    .card-raised {
        border-radius: 17px !important;
        box-shadow: 0 5px 24px 0 rgba(60,80,180,0.11), 0 1.5px 3px 0 rgba(0,0,0,0.07);
        border: none;
    }

    .highlight-card:hover {
        box-shadow: 0 8px 32px 0 rgba(60,80,180,0.13), 0 1.5px 3px 0 rgba(0,0,0,0.10);
        transform: translateY(-2px) scale(1.028);
    }
    
    .icon-circle {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 6px 0 rgba(60,140,231,0.12);
        font-size: 1.5rem;
    }

    /* Timeline */
    .timeline {
        border-left: 4px solid #e8eaf6;
        margin-left: 12px;
        padding-left: 18px;
        position: relative;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 32px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: -28px;
        top: 6px;
        width: 16px;
        height: 16px;
        background: linear-gradient(90deg, #005bea 0%, #3c8ce7 100%);
        border-radius: 50%;
        box-shadow: 0 1.5px 3px 0 rgba(0,0,0,0.07);
    }
    .timeline-date {
        font-size: 0.94rem;
        color: #6b7280;
        margin-bottom: 4px;
    }
    .amount-credit {
        font-size: 1.10rem;
        font-weight: 600;
    }

    /* Payment Table */
    #datatablesDash th, #datatablesDash td {
        vertical-align: middle;
        font-size: 1.08rem;
    }
    #datatablesDash .fa-brands, #datatablesDash .fa-solid {
        font-size: 1.2rem;
        color: #3c8ce7;
    }

    /* Sales Chart */
    #areaChart {
        width: 100%;
        min-height: 350px;
    }

    /* List group stats */
    .list-group-flush .list-group-item {
        background: #f4f7fb;
        border: none;
        border-radius: 14px;
        margin-bottom: 10px;
        box-shadow: 0 1px 4px 0 rgba(60,80,180,0.06);
    }
    .list-group-item h6 {
        font-weight: 600;
        color: #3c8ce7;
    }

    .btn-gradient-primary {
        background: linear-gradient(90deg, #005bea 0%, #3c8ce7 100%);
        border: none;
        color: #fff;
        transition: background .18s;
    }
    .btn-gradient-primary:hover, .btn-gradient-primary:focus {
        background: linear-gradient(90deg, #3c8ce7 0%, #005bea 100%);
        color: #fff;
    }

    /* Modal improvements */
    .modal-content {
        border-radius: 18px !important;
        box-shadow: 0 8px 32px 0 rgba(60,80,180,0.10), 0 1.5px 3px 0 rgba(0,0,0,0.06);
    }
    .modal-header, .modal-footer {
        border: none;
    }
    .modal-header {
        background: linear-gradient(90deg, #005bea 0%, #3c8ce7 100%);
        color: #fff;
        border-radius: 18px 18px 0 0 !important;
    }
    .modal-footer {
        border-radius: 0 0 18px 18px !important;
    }
    .modal-title {
        font-weight: 600;
    }

    /* Custom select */
    .form-select:focus {
        border-color: #3c8ce7;
        box-shadow: 0 0 0 .16rem rgba(60,140,231,0.08);
    }

    /* Responsive adjustments */
    @media (max-width: 900px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            padding: 25px 14px;
        }
        .dashboard-header img { margin-top: 18px; }
    }
</style>

<x-app-layout :route="'Dashboard'">
    <!-- Modal: Status de Cadastro -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Atenção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
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

    <div class="main-content app-content dashboard-gradient-bg">
        <div class="container-fluid">

            @if($status == 0)
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="p-3 d-grid border-bottom border-block-end-dashed">
                            <h5 class="card-title">Ativação de Conta</h5>
                            <p class="card-text">Para ativar sua conta, é necessário o envio de documentos. Por favor, envie os documentos para análise.</p>
                            <a href="{{ url('/enviar-doc') }}" class="btn btn-gradient-primary">Enviar Documentos</a>
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
            
            @if(isset($setting->gateway_banner_home) && !is_null($setting->gateway_banner_home))
               <div class="d-none d-sm-block mt-5 mb-5 row">
                    <img src="{{ $setting->gateway_banner_home }}" width="1280" height="126" class="rounded-5">
                </div>
            @endif

            <form class="mb-4 row g-3 align-items-end" method="GET" action="{{ route('dashboard') }}" id="filtroForm">
                <div class="col-sm-6 col-md-3">
                    <label for="produtoSelect" class="form-label fw-semibold text-primary">Produto</label>
                    <select id="produtoSelect" class="form-select" name="produto" onchange="document.getElementById('filtroForm').submit()" required>
                        <option value="todos" {{ request('produto') == 'todos' ? 'selected' : '' }}>Todos</option>
                        @foreach(auth()->user()->produtos as $produto)
                            <option value="{{ $produto->id }}" {{ request('produto') == $produto->id ? 'selected' : '' }}>{{ $produto->produto_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label for="periodoSelect" class="form-label fw-semibold text-primary">Período</label>
                    <select id="periodoSelect" class="form-select" name="periodo" onchange="submitPeriod()" required>
                        <option value="hoje" {{ request('periodo') == 'hoje' ? 'selected' : '' }}>Hoje</option>
                        <option value="ontem" {{ request('periodo') == 'ontem' ? 'selected' : '' }}>Ontem</option>
                        <option value="7dias" {{ request('periodo') == '7dias' ? 'selected' : '' }}>Últimos 7 dias</option>
                        <option value="30dias" {{ request('periodo') == '30dias' ? 'selected' : '' }}>Últimos 30 dias</option>
                        <option value="tudo" {{ request('periodo') == 'tudo' ? 'selected' : '' }}>Sempre</option>
                        <option value="personalizado">Personalizado</option>
                    </select>
                </div>
            </form>

            <!-- Cards resumo -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card card-raised highlight-card h-100">
                        <div class="card-body px-4 py-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <div class="display-6 fw-bold text-success">R$ {{ number_format(auth()->user()->saldo ?? 0, 2, ',', '.') }}</div>
                                    <div class="text-muted">Saldo disponível</div>
                                </div>
                                <div class="icon-circle bg-success text-white"><i class="fa-solid fa-dollar-sign"></i></div>
                            </div>
                            <div class="text-muted small">
                                <i class="fa-solid fa-clock text-warning me-1"></i>
                                <b>Pendente:</b>
                                <span class="text-warning">R$ {{number_format(auth()->user()->valor_saque_pendente, '2',',','.')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card card-raised highlight-card h-100">
                        <div class="card-body px-4 py-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <div class="display-6 fw-bold text-primary">R$ {{ number_format((clone $solicitacoes)->where('status', 'PAID_OUT')->sum('amount') ?? 0, 2, ',', '.') }}</div>
                                    <div class="text-muted">Vendas Realizadas</div>
                                </div>
                                <div class="icon-circle bg-primary text-white"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card card-raised highlight-card h-100">
                        <div class="card-body px-4 py-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <div class="display-6 fw-bold text-info">{{ (clone $solicitacoes)->where('status', 'PAID_OUT')->count() }}</div>
                                    <div class="text-muted">Quantidade de vendas</div>
                                </div>
                                <div class="icon-circle bg-info text-white"><i class="fa-solid fa-arrow-down-short-wide"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card card-raised highlight-card h-100">
                        <div class="card-body px-4 py-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    @php
                                        $paidOutSolicitacoes = (clone $solicitacoes)->where('status', 'PAID_OUT');
                                        $countPaidOut = $paidOutSolicitacoes->count();
                                        $ticketMedio = $countPaidOut > 0 ? $paidOutSolicitacoes->sum('amount') / $countPaidOut : 0;
                                    @endphp
                                    <div class="display-6 fw-bold text-secondary">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</div>
                                    <div class="text-muted">Ticket médio</div>
                                </div>
                                <div class="icon-circle bg-secondary text-white"><i class="fa-solid fa-arrows-rotate"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos e stats -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card card-raised h-100">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <h5 class="card-title mb-0">Estatísticas de vendas</h5>
                        </div>
                        <div class="card-body p-4">
                            <div id="areaChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-raised h-100">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <h5 class="card-title mb-0">Indicadores</h5>
                        </div>
                        <div class="card-body px-4 py-4 d-flex flex-column justify-content-center">
                            <ul class="list-group list-group-flush w-100">
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6>Abandono C.</h6>
                                        <div>
                                            <span id="label-abandono" class="fs-4 fw-bold text-primary">0%</span>
                                            <i onclick="setHidden('vis-abandono', 'label-abandono','0%')" id="vis-abandono" class="fs-4 cursor-pointer fa-solid fa-eye text-primary ms-2"></i>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6>Reembolso</h6>
                                        <div>
                                            <span id="label-reembolso" class="fs-4 fw-bold text-info">0%</span>
                                            <i onclick="setHidden('vis-reembolso', 'label-reembolso','0%')" id="vis-reembolso" class="fs-4 cursor-pointer fa-solid fa-eye text-info ms-2"></i>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6>Charge Back</h6>
                                        <div>
                                            <span id="label-chargeback" class="fs-4 fw-bold text-danger">0%</span>
                                            <i onclick="setHidden('vis-chargeback', 'label-chargeback','0%')" id="vis-chargeback" class="fs-4 cursor-pointer fa-solid fa-eye text-danger ms-2"></i>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6>MED</h6>
                                        <div>
                                            <span id="label-med" class="fs-4 fw-bold text-warning">0%</span>
                                            <i onclick="setHidden('vis-med', 'label-med','0%')" id="vis-med" class="fs-4 cursor-pointer fa-solid fa-eye text-warning ms-2"></i>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagamentos e Timeline -->
            <div class="row g-4 mt-1">
                <div class="col-lg-8">
                    <div class="card card-raised h-100">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <h5 class="card-title mb-0">Meios de Pagamento</h5>
                        </div>
                        <div class="card-body px-4 py-3">
                            <div class="table-responsive">
                                <table id="datatablesDash" class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Meios de Pagamento</th>
                                            <th>Aprovação</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="me-2"><i class="fa-brands fa-pix"></i></span>Pix
                                            </td>
                                            <td>
                                                @php
                                                    $totalFiltradas = (clone $solicitacoes)->count();
                                                    $totalAprovadas = (clone $solicitacoes)->where('status', 'PAID_OUT')->count();
                                                    $porcentagemAprovadas = 0;
                                                    if ($totalFiltradas > 0) {
                                                        $porcentagemAprovadas = ($totalAprovadas / $totalFiltradas) * 100;
                                                    }
                                                @endphp
                                                {{ number_format($porcentagemAprovadas, 2, ',', '.') }}%
                                            </td>
                                            <td>
                                                R$ {{ number_format((clone $solicitacoes)->where('status', 'PAID_OUT')->sum('amount') ?? 0, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="me-2"><i class="fa-solid fa-barcode"></i></span>Boleto
                                            </td>
                                            <td>0.00%</td>
                                            <td>R$ 0,00</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="me-2"><i class="fa-solid fa-credit-card"></i></span>Cartão
                                            </td>
                                            <td>0.00%</td>
                                            <td>R$ 0,00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-raised h-100">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <h5 class="card-title mb-0">Últimas vendas</h5>
                        </div>
                        <div class="card-body px-3 py-4">
                            <section>
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

    <!-- Modal: Período Personalizado -->
    <div class="modal fade" id="dateRangeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-body justify-center p-4">
                    <h5 class="mb-4 fw-semibold">Selecione o período</h5>
                    <div class="row">
                        <div class="mb-3 text-center col-md-6">
                            <strong>Data de Início</strong>
                            <div class="d-flex justify-content-center" id="calendarInicio"></div>
                        </div>
                        <div class="text-center col-md-6">
                            <strong>Data de Fim</strong>
                            <div class="d-flex justify-content-center" id="calendarFim"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer gap-2 mt-4 d-flex justify-content-end">
                    <button class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-gradient-primary" id="btnAplicarDatas">Aplicar</button>
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

        select.addEventListener("change", function () {
            if (select.value === "personalizado") {
                new bootstrap.Modal(modalEl).show();
            } else {
                form.submit();
            }
        });

        btnAplicar.addEventListener("click", function () {
            if (dataInicioSelecionada && dataFimSelecionada) {
                const inicioStr = dataInicioSelecionada.toISOString().split("T")[0];
                const fimStr = dataFimSelecionada.toISOString().split("T")[0];
                const texto = `${formatarDataBr(dataInicioSelecionada)} – ${formatarDataBr(dataFimSelecionada)}`;
                const valor = `${inicioStr}:${fimStr}`;

                let opExistente = select.querySelector('option[data-personalizado]');
                if (opExistente) select.removeChild(opExistente);

                let op = document.createElement("option");
                op.value = valor;
                op.textContent = texto;
                op.setAttribute("data-personalizado", "1");
                select.appendChild(op);
                select.value = valor;

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
                select.value = periodo;
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

        const now = new Date();
        const labels = [];
        const depositMap = {};

        for (let h = 0; h < 24; h++) {
            const hourLabel = `${h.toString().padStart(2, '0')}:00`;
            labels.push(hourLabel);
            depositMap[hourLabel] = 0;
        }

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
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            legend: { show: false },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: { categories: labels, labels: { rotate: 0 } },
            tooltip: { x: { show: true } },
            colors: ["{{ $setting->gateway_color ?? '#7367F0' }}"],
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0, stops: [0, 100] }
            }
        };

        const chartElement = document.querySelector("#areaChart");
        if (!chartElement) return;

        if (window.areaChartInstance) window.areaChartInstance.destroy();

        window.areaChartInstance = new ApexCharts(chartElement, options);
        window.areaChartInstance.render();

        window.addEventListener("resize", () => {
            if (window.areaChartInstance) window.areaChartInstance.render();
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