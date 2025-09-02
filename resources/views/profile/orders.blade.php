<style>
.orders-page-card-raised {
    border-radius: 17px !important;
    box-shadow: 0 5px 24px 0 rgba(60,80,180,0.11), 0 1.5px 3px 0 rgba(0,0,0,0.07);
    border: none;
}
.orders-page-highlight-card {
    background: linear-gradient(90deg, #f1f5fd 80%, #dbeafe 100%);
    transition: box-shadow .2s, transform .2s;
}
.orders-page-highlight-card:hover {
    box-shadow: 0 8px 32px 0 rgba(60,80,180,0.13), 0 1.5px 3px 0 rgba(0,0,0,0.10);
    transform: translateY(-2px) scale(1.028);
}
.orders-page-icon-circle {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 6px 0 rgba(60,140,231,0.12);
    font-size: 1.5rem;
}
.orders-page-shopify-table-wrapper {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px 0 rgba(60,80,180,0.08), 0 1.5px 3px 0 rgba(0,0,0,0.04);
    overflow: hidden;
    margin-bottom: 2rem;
}
.orders-page-shopify-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.97rem;
    background: #fff;
}
.orders-page-shopify-table thead tr {
    background: #f6f8fa;
    box-shadow: 0 2px 8px 0 rgba(60,80,180,0.06);
}
.orders-page-shopify-table thead th {
    color: #3c4a5a;
    font-weight: 500;
    padding: 0.85rem 0.7rem;
    border-bottom: 1.5px solid #e3e8ee;
    font-size: 0.99rem;
    letter-spacing: 0.01em;
    background: #f6f8fa;
}
.orders-page-shopify-table tbody tr {
    transition: background 0.18s;
}
.orders-page-shopify-table tbody tr:nth-child(even) {
    background: #f9fafb;
}
.orders-page-shopify-table tbody tr:hover {
    background: #f1f5fd;
}
.orders-page-shopify-table td {
    padding: 0.75rem 0.7rem;
    border: none;
    vertical-align: middle;
    color: #222;
}
.orders-page-shopify-table th:first-child, .orders-page-shopify-table td:first-child {
    border-top-left-radius: 14px;
}
.orders-page-shopify-table th:last-child, .orders-page-shopify-table td:last-child {
    border-top-right-radius: 14px;
}
.orders-page-shopify-table .orders-page-badge-status {
    font-size: 0.93rem;
    border-radius: 1.2rem;
    padding: 0.38em 1em;
    font-weight: 600;
    letter-spacing: 0.02em;
    border: none;
    background: #e3e8ee;
    color: #1a1a1a;
    box-shadow: none;
    display: inline-block;
}
.orders-page-shopify-table .orders-page-badge-status.pago {
    background: #e6f7ee;
    color: #1a7f37;
}
.orders-page-shopify-table .orders-page-badge-status.pendente {
    background: #fff7e6;
    color: #b26a00;
}
.orders-page-shopify-table .orders-page-action-group {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
}
.orders-page-shopify-table .orders-page-action-btn {
    background: #f4f7fb;
    border: none;
    border-radius: 50%;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s, box-shadow 0.15s;
    color: #3c8ce7;
    font-size: 1.1rem;
    box-shadow: 0 1px 4px 0 rgba(60,80,180,0.06);
    margin: 0;
    padding: 0;
}
.orders-page-shopify-table .orders-page-action-btn:hover {
    background: #e3e8ee;
    color: #005bea;
    box-shadow: 0 2px 8px 0 rgba(60,140,231,0.10);
}
.orders-page-shopify-table .orders-page-expand-btn {
    background: none;
    border: none;
    color: #b0b8c1;
    font-size: 1.05rem;
    padding: 0.2rem 0.3rem;
    vertical-align: middle;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.15s, background 0.15s;
    outline: none;
    margin: 0;
}
.orders-page-shopify-table .orders-page-expand-btn:focus,
.orders-page-shopify-table .orders-page-expand-btn:hover {
    color: #3c8ce7;
    background: #e3e8ee;
    border-radius: 50%;
}
.orders-page-shopify-table .orders-page-expand-btn .fa-chevron-up,
.orders-page-shopify-table .orders-page-expand-btn .fa-chevron-down {
    transition: transform 0.2s;
}
.orders-page-shopify-table .orders-page-expand-btn.active .fa-chevron-up {
    transform: rotate(180deg);
}
.orders-page-expand-row {
    background: #f9fafb;
    border-top: 1px solid #e3e8ee;
}
.orders-page-expand-content {
    padding: 1.1rem 1.5rem 1.1rem 3.5rem;
    font-size: 0.93rem;
    color: #444;
}
@media (max-width: 900px) {
    .orders-page-shopify-table th, .orders-page-shopify-table td { font-size: 0.93rem; padding: 0.6rem 0.3rem; }
}
</style>
<x-app-layout :route="'Pedidos'">
    <div class="container-fluid px-0 orders-page">
        <!-- Título e Filtro -->
        <div class="row align-items-center mb-4 gy-2 gx-3 px-2">
            <div class="col-12 col-lg-6 mb-2 mb-lg-0">
                <h2 class="fw-bold text-dark mb-0">Pedidos</h2>
            </div>
            <div class="col-12 col-lg-6">
                <form method="GET" action="{{ route('profile.orders') }}" id="filtroCompleto">
                    <div class="row gx-2 gy-2 align-items-center">
                        <div class="col-12 col-md-7">
                            <input type="search" class="form-control form-control-lg rounded-pill px-4" id="buscar" name="buscar" placeholder="Buscar por nome, email ou CPF" value="{{ request('buscar') }}" autofocus>
                        </div>
                        <div class="col-8 col-md-4">
                            <select class="form-select form-select-lg rounded-pill px-3" id="status" name="status">
                                <option value="pagos" {{ request('status') == 'pagos' ? 'selected' : '' }}>Pagos</option>
                                <option value="pendentes" {{ request('status') == 'pendentes' ? 'selected' : '' }}>Pendentes</option>
                                <option value="med" {{ request('status') == 'med' ? 'selected' : '' }}>MED</option>
                                <option value="chargeback" {{ request('status') == 'chargeback' ? 'selected' : '' }}>Chargeback</option>
                                <option value="reembolso" {{ request('status') == 'reembolso' ? 'selected' : '' }}>Reembolso</option>
                                <option value="todos" {{ request('status') == 'todos' ? 'selected' : '' }}>Todos</option>
                            </select>
                        </div>
                        <div class="col-4 col-md-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary rounded-circle shadow-sm" style="width:44px;height:44px;"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cards Resumo -->
        <div class="container-fluid px-2 mb-4">
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card card-raised highlight-card h-100">
                        <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="display-6 fw-bold text-success">{{ (clone $orders)->where('status', 'pago')->count() }}</div>
                                <div class="text-muted">Pedidos Pagos</div>
                            </div>
                            <div class="icon-circle bg-success text-white"><i class="fa-solid fa-check"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card card-raised highlight-card h-100">
                        <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="display-6 fw-bold text-warning">{{ (clone $orders)->where('status', 'gerado')->count() }}</div>
                                <div class="text-muted">Pedidos Pendentes</div>
                            </div>
                            <div class="icon-circle bg-warning text-white"><i class="fa-solid fa-clock"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card card-raised highlight-card h-100">
                        <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="display-6 fw-bold text-primary">{{ 'R$ ' . number_format((clone $orders)->where('status', 'pago')->sum('valor_total'), 2, ',', '.') }}</div>
                                <div class="text-muted">Valor Pago</div>
                            </div>
                            <div class="icon-circle bg-primary text-white"><i class="fa-solid fa-money-bill-wave"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card card-raised highlight-card h-100">
                        <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="display-6 fw-bold text-secondary">{{ 'R$ ' . number_format((clone $orders)->where('status', 'gerado')->sum('valor_total'), 2, ',', '.') }}</div>
                                <div class="text-muted">Valor Pendente</div>
                            </div>
                            <div class="icon-circle bg-secondary text-white"><i class="fa-solid fa-hourglass-half"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela Pedidos -->
        <div class="row px-2">
            <div class="col-12">
                <div class="orders-page-shopify-table-wrapper">
                    <div class="table-responsive p-0">
                        <table class="orders-page-shopify-table" id="orders-table-expand">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th class="text-center">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ordersPage as $order)
                                    <tr class="orders-page-main-row">
                                        <td>{{ $order->checkout->produto_name ?? $order->checkout_name }}</td>
                                        <td>{{ 'R$ ' . number_format($order->valor_total, 2, ',', '.') }}</td>
                                        <td>
                                            @if ($order->status == 'pago')
                                                <span class="orders-page-badge-status pago">Pago</span>
                                            @else
                                                <span class="orders-page-badge-status pendente">Pendente</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y \à\s H:i:s') }}</td>
                                        <td class="text-center">
                                            <div class="orders-page-action-group">
                                                <button class="orders-page-action-btn" title="Consultar pedido">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                                <button class="orders-page-expand-btn" title="Expandir detalhes" onclick="ordersPageToggleExpand(this)">
                                                    <i class="fa fa-chevron-down"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="orders-page-expand-row" style="display:none;">
                                        <td colspan="5" class="orders-page-expand-content">
                                            <div><strong>Nome:</strong> {{ $order->name }}</div>
                                            <div><strong>CPF:</strong> {{ $order->cpf }}</div>
                                            <div><strong>Telefone:</strong> {{ $order->telefone }}</div>
                                            <div><strong>Email:</strong> {{ $order->email }}</div>
                                            <div><strong>Endereço:</strong>
                                                @if (isset($order->checkout) && $order->checkout->produto_tipo == 'fisico')
                                                    {{ $order->endereco . ', Nº' . $order->numero . ' ' . $order->bairro . ', ' . $order->cidade . '-' . $order->estado . ' CEP: ' . $order->cep }}
                                                @else
                                                    ---
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Nenhum pedido encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($ordersPage, 'links'))
                    <div class="d-flex justify-content-end align-items-center px-4 py-3">
                        {{ $ordersPage->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
// Removido DataTables, apenas filtro automático
        document.addEventListener("DOMContentLoaded", function () {
            const input = document.getElementById('buscar');
            const status = document.getElementById('status');
            let timeout = null;
            input.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    document.getElementById('filtroCompleto').submit();
                }, 500);
            });
            status.addEventListener('change', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    document.getElementById('filtroCompleto').submit();
                }, 500);
            });
        });
        function ordersPageToggleExpand(btn) {
            const mainRow = btn.closest('tr');
            const expandRow = mainRow.nextElementSibling;
            const icon = btn.querySelector('i');
            const isOpen = window.getComputedStyle(expandRow).display !== 'none';
            if (isOpen) {
                expandRow.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                btn.classList.remove('active');
            } else {
                // Fecha todos os outros
                document.querySelectorAll('.orders-page-expand-row').forEach(function(row) { row.style.display = 'none'; });
                document.querySelectorAll('.orders-page-expand-btn').forEach(function(b) {
                    b.classList.remove('active');
                    b.querySelector('i').classList.remove('fa-chevron-up');
                    b.querySelector('i').classList.add('fa-chevron-down');
                });
                // Abre o atual
                expandRow.style.display = 'table-row';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                btn.classList.add('active');
            }
        }
    </script>
</x-app-layout>
