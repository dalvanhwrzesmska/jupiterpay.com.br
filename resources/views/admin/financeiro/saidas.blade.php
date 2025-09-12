<x-app-layout :route="'[ADMIN] Saídas'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Saídas</h1>
                </div>
            </div>
            <!-- Start:: row-1 -->
             <!-- Start:: row-1 -->
             <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ $totalaprovadas }}</div>
                                    <div class="card-text">Aprovadas (Total)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ $totalaprovadasHoje }}</div>
                                    <div class="card-text">Aprovadas (Hoje)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ $totalaprovadasMes }}</div>
                                    <div class="card-text">Aprovadas (Mês)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ $totalsolicitacoes }}</div>
                                    <div class="card-text">Transações geral</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->





            <!-- Start:: row-2 -->
            <div class="row">
                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($valorAprovadoTotal, 2, ',', '.') }}</div>
                                    <div class="card-text">Aprovadas (Bruto)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($valorAprovadoHoje, 2, ',', '.') }}</div>
                                    <div class="card-text">Aprovadas (Hoje)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($valorAprovadoMes, 2, ',', '.') }}</div>
                                    <div class="card-text">Aprovadas (Mês)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start::row-2 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-financeiro-saidas" class="table text-nowrap">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">User ID</th>
                                            <th scope="col">Transação ID</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Valor Líquido</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Chave PIX</th>
                                            <th scope="col">Tipo de Chave</th>
                                            <th scope="col">Data</th>
                                            <th scope="col">Taxa </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cashOuts as $cashOut)
                                        <tr>
                                            <td>{{ $cashOut->user_id }}</td>
                                            <td>{{ $cashOut->externalreference }}</td>
                                            <td>{{ number_format($cashOut->amount, 2, ',', '.') }}</td>
                                            <td>{{ number_format($cashOut->cash_out_liquido, 2, ',', '.') }}</td>
                                            <td>
                                                @switch($cashOut->status)
                                                @case('COMPLETED')
                                                <span class="badge bg-success">Aprovado</span>
                                                @break
                                                @case('PENDING')
                                                <span class="badge bg-warning">Pendente</span>
                                                @break
                                                @case('CANCELLED')
                                                <span class="badge bg-danger-transparent">Cancelado</span>
                                                @break
                                                @default
                                                <span class="badge">Desconhecido</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $cashOut->beneficiaryname }}</td>
                                            <td>{{ $cashOut->beneficiarydocument }}</td>
                                            <td>{{ $cashOut->pixkey }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cashOut->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            <td> R$ {{ number_format((float)$cashOut->amount - (float)$cashOut->cash_out_liquido, '2', ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="11">Nenhum registro encontrado</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="dateFilterModal" tabindex="-1" role="dialog" aria-labelledby="dateFilterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="shadow-lg modal-content">
                        <form method="GET" action="{{ route('admin.financeiro.saidas') }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dateFilterModalLabel">Filtrar por Data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="data_inicio">Data Início</label>
                                    <input type="date" class="form-control" name="data_inicio" id="data_inicio" value="{{ $dataInicio }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="data_fim">Data Fim</label>
                                    <input type="date" class="form-control" name="data_fim" id="data_fim" value="{{ $dataFim }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        $("#table-financeiro-saidas").DataTable({
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
                    $('#table-financeiro-saidas_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                }
        });
    });
    </script>
</x-app-layout>
