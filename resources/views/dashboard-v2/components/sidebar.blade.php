<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">

            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span
                        class="toggle-line"></span></span></button>

        </div><a class="navbar-brand" href="{{ route('dashboard.v2') }}">
            <div class="d-flex align-items-center py-3"><img class="me-2"
                    src="https://i.imgur.com/VNInZj4.png" alt="" width="100" />
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.v2') }}" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-chart-pie"></span></span><span
                                class="nav-link-text ps-1">Dashboard</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">App
                        </div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div>
                    <a class="nav-link dropdown-indicator" href="#relatorios" role="button"
                        data-bs-toggle="collapse" aria-controls="relatorios">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-chart-line"></span></span><span
                                class="nav-link-text ps-1">Relatórios</span>
                        </div>
                    </a>
                    <ul class="nav collapse" id="relatorios">
                        <li class="nav-item"><a class="nav-link" href="{{ route('profile.relatorio.pixentrada.v2') }}">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Entradas</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('profile.relatorio.pixsaida.v2') }}">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Saídas</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <a class="nav-link" href="{{ route('profile.financeiro.v2') }}" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-dollar-sign"></span></span><span
                                class="nav-link-text ps-1">Financeiro</span>
                        </div>
                    </a>
                    <a class="nav-link" href="{{ route('profile.checkout-list.v2') }}" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-comments"></span></span><span class="nav-link-text ps-1">Produtos</span>
                        </div>
                    </a>
                    <a class="nav-link" href="{{ route('profile.orders.v2') }}" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-shopping-cart"></span></span><span class="nav-link-text ps-1">Pedidos</span>
                        </div>
                    </a>
                    <a class="nav-link" href="{{ route('profile.documentacao.v2') }}" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-question-circle"></span></span><span class="nav-link-text ps-1">Documentação</span>
                        </div>
                    </a>
                    <a class="nav-link" href="{{ route('profile.chavesapi.v2') }}" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-puzzle-piece"></span></span><span class="nav-link-text ps-1">Chaves de API</span>
                        </div>
                    </a>
                    <a class="nav-link" href="{{ route('webhook.index.v2') }}" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-code-branch"></span></span><span class="nav-link-text ps-1">Webhooks</span>
                        </div>
                    </a>
                   

                    <!-- parent pages--><a class="nav-link dropdown-indicator" href="#support-desk" role="button"
                        data-bs-toggle="collapse" aria-expanded="false" aria-controls="support-desk">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-ticket-alt"></span></span><span class="nav-link-text ps-1">Suporte</span>
                        </div>
                    </a>
                    <ul class="nav collapse" id="support-desk">
                        <li class="nav-item"><a class="nav-link" href="app/support-desk/table-view.html">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Table
                                        view</span>
                                </div>
                            </a>
                            <!-- more inner pages-->
                        </li>
                        <li class="nav-item"><a class="nav-link" href="app/support-desk/reports.html">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Reports</span>
                                </div>
                            </a>
                            <!-- more inner pages-->
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <!-- label-->
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Administração</div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div>
                    <a class="nav-link" href="/administrador/dashboard" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-tachometer-alt"></span></span><span class="nav-link-text ps-1">Dashboard</span></div>
                    </a>
                    <a class="nav-link" href="/administrador/usuarios?status=todos" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-users"></span></span><span class="nav-link-text ps-1">Usuários</span></div>
                    </a>
                    <a class="nav-link" href="/administrador/aprovar-saques" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-check-double"></span></span><span class="nav-link-text ps-1">Aprovar saques</span></div>
                    </a>
                    <a class="nav-link dropdown-indicator" href="#financeiro" role="button" data-bs-toggle="collapse" aria-controls="financeiro">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calculator"></span></span><span class="nav-link-text ps-1">Financeiro</span></div>
                    </a>
                    <ul class="nav collapse" id="financeiro">
                        <li class="nav-item"><a class="nav-link" href="/administrador/financeiro/transacoes"><span class="nav-link-text ps-1">Transações</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/administrador/financeiro/carteiras"><span class="nav-link-text ps-1">Carteiras</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/administrador/financeiro/entradas"><span class="nav-link-text ps-1">Entradas</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/administrador/financeiro/saidas"><span class="nav-link-text ps-1">Saídas</span></a></li>
                    </ul>
                    <a class="nav-link dropdown-indicator" href="#transacoes" role="button" data-bs-toggle="collapse" aria-controls="transacoes">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-exchange-alt"></span></span><span class="nav-link-text ps-1">Criar Transações</span></div>
                    </a>
                    <ul class="nav collapse" id="transacoes">
                        <li class="nav-item"><a class="nav-link" href="/administrador/transacoes/entrada"><span class="nav-link-text ps-1">Entrada</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/administrador/transacoes/saida"><span class="nav-link-text ps-1">Saída</span></a></li>
                    </ul>
                    <a class="nav-link dropdown-indicator" href="#configuracoes" role="button" data-bs-toggle="collapse" aria-controls="configuracoes">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-cogs"></span></span><span class="nav-link-text ps-1">Configurações</span></div>
                    </a>
                    <ul class="nav collapse" id="configuracoes">
                        <li class="nav-item"><a class="nav-link" href="/administrador/ajustes/gerais"><span class="nav-link-text ps-1">Gerais</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/administrador/ajustes/niveis"><span class="nav-link-text ps-1">Níveis</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/administrador/ajustes/adquirentes"><span class="nav-link-text ps-1">Adquirentes</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/administrador/ajustes/apoio"><span class="nav-link-text ps-1">Gerentes</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="/administrador/ajustes/landing-page"><span class="nav-link-text ps-1">Landing Page</span></a></li>
                    </ul>
                </li>
            </ul>
            <div class="settings my-3">
                <div class="card shadow-none">
                    <div class="card-body alert mb-0" role="alert">
                        <div class="btn-close-falcon-container">
                            <button class="btn btn-link btn-close-falcon p-0" aria-label="Close"
                                data-bs-dismiss="alert"></button>
                        </div>
                        <div class="text-center"><img
                                src="/dashboard-v2/assets/img/icons/spot-illustrations/navbar-vertical.png" alt=""
                                width="80" />
                            <p class="fs-11 mt-2">Aproveite nossos descontos! <br />Pague menos taxas
                            </p>
                            <div class="d-grid"><a class="btn btn-sm btn-primary"
                                    href="{{ route('planos.index.v2') }}" role="button">Assinar agora</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>