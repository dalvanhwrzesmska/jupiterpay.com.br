<x-app-layout :route="'[ADMIN] Carteiras'">
    <div class="main-content app-content">
        <div class="container-fluid">


             <!-- Start::page-header -->
             <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Carteiras</h1>
                </div>
            </div>

            <!-- Start:: row-2 -->
            <div class="row">
                <div class="mb-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($total_em_carteiras ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Total em carteiras</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-wallet"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($totalBrutoGateway ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Total no gateway</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-wallet"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-2 -->

            <!-- Start::row-2 -->
            <div class="mb-3 row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between d-flex align-items-center">
                            <div class="card-title">
                                TOP 3 com mais vendas
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success">
                                <ul>
                                    @forelse ($top3Users as $topUser)
                                        <li>
                                            <strong>User:</strong> {{ $topUser->user->name }} |
                                            <strong>Saldo:</strong> R$ {{ number_format($topUser->total_amount, 2, ',', '.') }} |
                                        </li>
                                    @empty
                                        <li>Nenhum usuário encontrado</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between d-flex align-items-center">
                            <div class="card-title">
                                Relatório de Usuários
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-carteiras" class="table text-nowrap">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">User ID</th>
                                            <th scope="col">Faturamento</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Telefone</th>
                                            <th scope="col">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($usuarios as $usuario)
                                            <tr>
                                                <td>{{ $usuario->user_id }}</td>
                                                <td>R$ {{ number_format($usuario->depositos()->where('status','PAID_OUT')->sum('amount'), 2, ',', '.') }}</td>
                                                <td>{{ $usuario->email }}</td>
                                                <td>{{ $usuario->telefone }}</td>
                                                <td>
                                                    <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $usuario->telefone) }}"
                                                    target="_blank"
                                                    class="btn btn-sm gateway-badge-success">WhatsApp</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">Nenhum registro encontrado</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        $("#table-carteiras").DataTable({
            responsive: true,
            info:false,
            ordering: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            dom: '<"top"f>rt<"bottom"p><"clear">',
                initComplete: function() {
                    // Muda o placeholder do input de busca
                    $('#table-carteiras_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                }
        });
    });
    </script>
</x-app-layout>
