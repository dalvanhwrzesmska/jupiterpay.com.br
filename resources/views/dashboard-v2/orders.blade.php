@extends('dashboard-v2.layout')

@section('content')

    <div class="card mb-3" id="ordersTable"
        data-list='{"valueNames":["order","date","address","status","amount"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Pedidos</h5>
                </div>
                <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                    <div class="d-none" id="orders-bulk-actions">
                        <div class="d-flex">
                            <select class="form-select form-select-sm" aria-label="Ações em massa">
                                <option selected="">Ações em massa</option>
                                <option value="Refund">Reembolsar</option>
                                <option value="Delete">Excluir</option>
                                <option value="Archive">Arquivar</option>
                            </select>
                            <button class="btn btn-falcon-default btn-sm ms-2" type="button">Aplicar</button>
                        </div>
                    </div>
                    <div id="orders-actions">
                        <button class="btn btn-falcon-default btn-sm mx-2" type="button"><span class="fas fa-filter"
                                data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">Filtrar</span></button>
                        <button class="btn btn-falcon-default btn-sm" type="button"><span class="fas fa-external-link-alt"
                                data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">Exportar</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                <table class="table table-sm table-striped fs-10 mb-0 overflow-hidden">
                    <thead class="bg-200">
                        <tr>
                            <th>
                                <div class="form-check fs-9 mb-0 d-flex align-items-center">
                                    <input class="form-check-input" id="checkbox-bulk-customers-select" type="checkbox"
                                        data-bulk-select='{"body":"table-orders-body","actions":"orders-bulk-actions","replacedElement":"orders-actions"}' />
                                </div>
                            </th>
                            <th class="text-900 sort pe-1 align-middle white-space-nowrap" data-sort="order">Pedido</th>
                            <th class="text-900 sort pe-1 align-middle white-space-nowrap pe-7" data-sort="date">Data</th>
                            <th class="text-900 sort pe-1 align-middle white-space-nowrap" data-sort="address"
                                style="min-width: 12.5rem;">Destinatário</th>
                            <th class="text-900 sort pe-1 align-middle white-space-nowrap text-center" data-sort="status">
                                Status</th>
                            <th class="text-900 sort pe-1 align-middle white-space-nowrap text-end" data-sort="amount">
                                Valor</th>
                            <th class="no-sort"></th>
                        </tr>
                    </thead>
                    <tbody class="list" id="table-orders-body">
                        @forelse ($ordersPage as $order)
                        <tr class="btn-reveal-trigger">
                            <td class="align-middle" style="width: 28px;">
                                <div class="form-check fs-9 mb-0 d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" data-bulk-select-row="data-bulk-select-row" />
                                </div>
                            </td>
                            <td class="order py-2 align-middle white-space-nowrap">
                                <a href="#"><strong>#{{ $order->id }}</strong></a> por
                                <strong class="text-muted">{{ $order->name }}</strong><br />
                                <a href="mailto:{{ $order->email }}">{{ $order->email }}</a>
                            </td>
                            <td class="date py-2 align-middle text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                            <td class="address py-2 align-middle white-space-nowrap">
                                {{ $order->endereco ?? '-' }}
                                <p class="mb-0 text-500 text-muted">{{ $order->checkout->produto_name ?? $order->checkout_name }}</p>
                            </td>
                            <td class="status py-2 align-middle text-center fs-9 white-space-nowrap">
                                @if ($order->status == 'pago')
                                    <span class="badge badge rounded-pill d-block badge-subtle-success">Pago<span class="ms-1 fas fa-check" data-fa-transform="shrink-2"></span></span>
                                @elseif ($order->status == 'gerado')
                                    <span class="badge badge rounded-pill d-block badge-subtle-warning">Pendente<span class="ms-1 fas fa-stream" data-fa-transform="shrink-2"></span></span>
                                @elseif ($order->status == 'processing')
                                    <span class="badge badge rounded-pill d-block badge-subtle-primary">Processando<span class="ms-1 fas fa-redo" data-fa-transform="shrink-2"></span></span>
                                @elseif ($order->status == 'on_hold')
                                    <span class="badge badge rounded-pill d-block badge-subtle-secondary">Em espera<span class="ms-1 fas fa-ban" data-fa-transform="shrink-2"></span></span>
                                @else
                                    <span class="badge badge rounded-pill d-block badge-subtle-secondary">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="amount py-2 align-middle text-end text-muted fs-9 fw-medium">R$ {{ number_format($order->valor_total, 2, ',', '.') }}</td>
                            <td class="py-2 align-middle white-space-nowrap text-end">
                                <div class="dropdown font-sans-serif position-static">
                                    <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal" type="button"
                                        data-bs-toggle="dropdown" data-boundary="viewport"
                                        aria-haspopup="true" aria-expanded="false"><span
                                            class="fas fa-ellipsis-h fs-10"></span></button>
                                    <div class="dropdown-menu dropdown-menu-end border py-0">
                                        <div class="py-2">
                                            <a class="dropdown-item" href="#">Detalhes</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#">Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Nenhum pedido encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex align-items-center justify-content-center">
                <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous"
                    data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="pagination mb-0"></ul>
                <button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next"
                    data-list-pagination="next"><span class="fas fa-chevron-right"> </span></button>
            </div>
        </div>
    </div>
@endsection