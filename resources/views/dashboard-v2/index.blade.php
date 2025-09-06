@extends('dashboard-v2.layout')

@section('content')
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
                    <a href="{{ url('/enviar-doc') }}" class="btn btn-falcon-success mr-1 mb-1">Enviar Documentos</a>
                </div>
            </div>
        </div>
    </div>

    @if($status == 0 && $banido == 0)
        <div class="p-5 container-xl">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="p-3 d-grid border-bottom border-block-end-dashed">
                            <h5 class="card-title">Ativação de Conta</h5>
                            <p class="card-text">Para ativar sua conta, é necessário o envio de documentos. Por favor, envie os documentos para análise.</p>
                            <a href="{{ url('/enviar-doc') }}" class="btn btn-falcon-info mr-1 mb-1">Enviar Documentos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($banido == 1)
        <div class="p-5 container-xl">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="p-3 d-grid border-bottom border-block-end-dashed">
                            <h5 class="card-title">Sua conta está em Bloqueada</h5>
                            <p class="card-text">Em caso de dúvidas entre em contato com o suporte.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($status == 5 && $banido == 0)
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

    @if($status == 1 && $banido == 0)
    {{-- Cards de resumo --}}

    <div class="row g-3 mb-3">
        <div class="col-md-6 col-xxl-3">
            <div class="card h-md-100 ecommerce-card-min-width">
                <div class="card-header pb-0">
                    <h6 class="mb-0 mt-2 d-flex align-items-center">Saldo disponível</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-end">
                    <div class="row">
                        <div class="col">
                            <p class="font-sans-serif lh-1 mb-1 fs-5 text-success">R$
                                {{ number_format(auth()->user()->saldo ?? 0, 2, ',', '.') }}</p>
                            <span class="badge badge-subtle-warning rounded-pill fs-11">Pendente: R$
                                {{number_format(auth()->user()->valor_saque_pendente, '2', ',', '.')}}</span>
                        </div>
                        <div class="col-auto ps-0">
                            <span class="fa-solid fa-dollar-sign text-success fs-2"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-md-100">
                <div class="card-header pb-0">
                    <h6 class="mb-0 mt-2">Vendas Realizadas</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-end">
                    <div class="row justify-content-between">
                        <div class="col-auto align-self-end">
                            <div class="fs-5 fw-normal font-sans-serif text-700 lh-1 mb-1">
                                {{ number_format((clone $solicitacoes)->where('status', 'PAID_OUT')->sum('amount') ?? 0, 2, ',', '.') }}
                            </div><span class="badge rounded-pill fs-11 bg-200 text-primary"><span
                                    class="fas fa-caret-up me-1"></span>0.0%</span>
                        </div>
                        <div class="col-auto ps-0 mt-n4">
                            <div class="echart-default-total-order"
                                data-echarts='{"tooltip":{"trigger":"axis","formatter":"{b0} : {c0}"},"xAxis":{"data":["Week 4","Week 5","Week 6","Week 7"]},"series":[{"type":"line","data":[20,40,100,120],"smooth":true,"lineStyle":{"width":3}}],"grid":{"bottom":"2%","top":"2%","right":"0","left":"10px"}}'
                                data-echart-responsive="true"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-md-100">
                <div class="card-body">
                    <div class="row h-100 justify-content-between g-0">
                        <div class="col-5 col-sm-6 col-xxl pe-2">
                            <h6 class="mt-1">Quantidade de Vendas</h6>
                            <div class="fs-11 mt-3">
                                <div class="d-flex flex-between-center mb-1">
                                    <div class="d-flex align-items-center"><span class="dot bg-primary"></span><span
                                            class="fw-semi-bold">---</span></div>
                                    <div class="d-xxl-none">--%</div>
                                </div>
                                <div class="d-flex flex-between-center mb-1">
                                    <div class="d-flex align-items-center"><span class="dot bg-info"></span><span
                                            class="fw-semi-bold">--</span></div>
                                    <div class="d-xxl-none">--%</div>
                                </div>
                                <div class="d-flex flex-between-center mb-1">
                                    <div class="d-flex align-items-center"><span class="dot bg-300"></span><span
                                            class="fw-semi-bold">--</span></div>
                                    <div class="d-xxl-none">--%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto position-relative">
                            <div class="echart-market-share"></div>
                            <div class="position-absolute top-50 start-50 translate-middle text-1100 fs-7">
                                {{ (clone $solicitacoes)->where('status', 'PAID_OUT')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-md-100 ecommerce-card-min-width">
                <div class="card-header pb-0">
                    <h6 class="mb-0 mt-2">Ticket médio</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-end">
                    <div class="row">
                        <div class="col">
                            @php
                                $paidOutSolicitacoes = (clone $solicitacoes)->where('status', 'PAID_OUT');
                                $countPaidOut = $paidOutSolicitacoes->count();
                                $ticketMedio = $countPaidOut > 0 ? $paidOutSolicitacoes->sum('amount') / $countPaidOut : 0;
                            @endphp
                            <p class="font-sans-serif lh-1 mb-1 fs-5 text-secondary">R$
                                {{ number_format($ticketMedio, 2, ',', '.') }}</p>
                        </div>
                        <div class="col-auto ps-0">
                            <span class="fa-solid fa-arrows-rotate text-secondary fs-2"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Gráfico e indicadores --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card h-100 me-lg-0">
                <div class="card-header">
                    <div class="row flex-between-center">
                        <div class="col-auto">
                            <h6 class="mb-0">Estatísticas de vendas</h6>
                        </div>
                        <div class="col-auto d-flex">
                            <select class="form-select form-select-sm select-month me-2">
                                <option value="0">Janeiro</option>
                                <option value="1">Fevereiro</option>
                                <option value="2">Março</option>
                                <option value="3">Abril</option>
                                <option value="4">Maio</option>
                                <option value="5">Junho</option>
                                <option value="6">Julho</option>
                                <option value="7">Agosto</option>
                                <option value="8">Setembro</option>
                                <option value="9">Outubro</option>
                                <option value="10">Novembro</option>
                                <option value="11">Dezembro</option>
                            </select>
                            <div class="dropdown font-sans-serif btn-reveal-trigger">
                                <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal"
                                    type="button" id="dropdown-total-sales" data-bs-toggle="dropdown" data-boundary="viewport"
                                    aria-haspopup="true" aria-expanded="false"><span
                                        class="fas fa-ellipsis-h fs-11"></span></button>
                                <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-total-sales">
                                    <a class="dropdown-item" href="#">Ver</a><a class="dropdown-item" href="#">Exportar</a>
                                    <div class="dropdown-divider"></div><a class="dropdown-item text-danger"
                                        href="#">Remover</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body h-100 pe-0">
                    <!-- Find the JS file for the following chart at: src\js\charts\echarts\total-sales.js-->
                    <!-- If you are not using gulp based workflow, you can find the transpiled code at: public\assets\js\theme.js-->
                    <div class="echart-line-total-sales h-100" data-echart-responsive="true"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 h-100">
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
                                    <i onclick="setHidden('vis-abandono', 'label-abandono','0%')" id="vis-abandono"
                                        class="fs-4 cursor-pointer fa-solid fa-eye text-primary ms-2"></i>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6>Reembolso</h6>
                                <div>
                                    <span id="label-reembolso" class="fs-4 fw-bold text-info">0%</span>
                                    <i onclick="setHidden('vis-reembolso', 'label-reembolso','0%')" id="vis-reembolso"
                                        class="fs-4 cursor-pointer fa-solid fa-eye text-info ms-2"></i>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6>Charge Back</h6>
                                <div>
                                    <span id="label-chargeback" class="fs-4 fw-bold text-danger">0%</span>
                                    <i onclick="setHidden('vis-chargeback', 'label-chargeback','0%')" id="vis-chargeback"
                                        class="fs-4 cursor-pointer fa-solid fa-eye text-danger ms-2"></i>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6>MED</h6>
                                <div>
                                    <span id="label-med" class="fs-4 fw-bold text-warning">0%</span>
                                    <i onclick="setHidden('vis-med', 'label-med','0%')" id="vis-med"
                                        class="fs-4 cursor-pointer fa-solid fa-eye text-warning ms-2"></i>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6 col-xxl-3">
            <div class="card h-md-100 ecommerce-card-min-width">
                <div class="card-header pb-0">
                    <h6 class="mb-0 mt-2 d-flex align-items-center">Vendas da Semana<span class="ms-1 text-400"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Calculado de acordo com as vendas da última semana"><span class="far fa-question-circle"
                                data-fa-transform="shrink-1"></span></span></h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-end">
                    <div class="row">
                        <div class="col">
                            <p class="font-sans-serif lh-1 mb-1 fs-5">$0K</p><span
                                class="badge badge-subtle-success rounded-pill fs-11">+0%</span>
                        </div>
                        <div class="col-auto ps-0">
                            <div class="echart-bar-weekly-sales h-100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-md-100">
                <div class="card-header pb-0">
                    <h6 class="mb-0 mt-2">Total de Pedidos</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-end">
                    <div class="row justify-content-between">
                        <div class="col-auto align-self-end">
                            <div class="fs-5 fw-normal font-sans-serif text-700 lh-1 mb-1">0K</div><span
                                class="badge rounded-pill fs-11 bg-200 text-primary"><span
                                    class="fas fa-caret-up me-1"></span>0%</span>
                        </div>
                        <div class="col-auto ps-0 mt-n4">
                            <div class="echart-default-total-order"
                                data-echarts='{"tooltip":{"trigger":"axis","formatter":"{b0} : {c0}"},"xAxis":{"data":["Semana 4","Semana 5","Semana 6","Semana 7"]},"series":[{"type":"line","data":[20,40,100,120],"smooth":true,"lineStyle":{"width":3}}],"grid":{"bottom":"2%","top":"2%","right":"0","left":"10px"}}'
                                data-echart-responsive="true"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif
@endsection