@props([
    'user',
    'gerentes',
    'gateways' => [],
])

<style>
    .modal-editar-usuario .modal-content {
        min-height: 70vh;
        max-height: 90vh;
        overflow: hidden;
    }
    .modal-editar-usuario .modal-body {
        overflow-y: auto;
        max-height: 70vh;
        padding-bottom: 90px !important;
        background: transparent !important;
    }
    .modal-editar-usuario .nav-tabs {
        margin-top: 0.5rem;
        border-bottom: 1px solid #e9ecef;
        background: transparent;
        border-radius: 1rem 1rem 0 0;
        padding: 0.25rem 1rem 0 1rem;
        overflow-x: auto;
        flex-wrap: nowrap;
        width: 100%;
    }
    .modal-editar-usuario .nav-tabs .nav-link {
        border: none;
        background: transparent;
        color: #495057;
        font-weight: 500;
        margin-right: 1rem;
        border-radius: 2rem 2rem 0 0;
        transition: background 0.2s, color 0.2s;
        white-space: nowrap;
    }
    .modal-editar-usuario .nav-tabs .nav-link.active {
        background: #e9ecef;
        color: #0d6efd;
        font-weight: 600;
    }
    .modal-editar-usuario .modal-footer-center {
        display: flex;
        justify-content: center;
        align-items: center;
        border-top: 1px solid #e9ecef;
        background: transparent;
        padding: 1rem 1rem;
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10;
    }
    @media (max-width: 576px) {
        .modal-editar-usuario .modal-body {
            padding-bottom: 130px !important;
        }
        .modal-editar-usuario .nav-tabs {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }
        .modal-editar-usuario .nav-tabs .nav-link {
            margin-right: 0.5rem;
            font-size: 0.95rem;
        }
    }
    .modal-editar-usuario .taxa-cartao-table th {
        background: #f1f3f9;
        color: #0d6efd;
        text-align: center;
        font-weight: 600;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .modal-editar-usuario .taxa-cartao-table td {
        text-align: center;
        vertical-align: middle;
        background: #fff;
        border-bottom: 1px solid #e9ecef;
    }
    .modal-editar-usuario .taxa-cartao-table input[type="text"] {
        text-align: center;
        border-radius: 2rem;
        border: 1px solid #ced4da;
        font-weight: 500;
        width: 90px;
        margin: 0 auto;
    }
    .modal-editar-usuario .taxa-cartao-table tr:last-child td {
        border-bottom: none;
    }
    .modal-editar-usuario .taxa-cartao-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .modal-editar-usuario .taxa-cartao-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: flex-start;
    }
    .modal-editar-usuario .taxa-cartao-box {
        background: #f8f9fa;
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        padding: 0.75rem 0.5rem 0.5rem 0.5rem;
        width: 110px;
        min-width: 90px;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    .modal-editar-usuario .taxa-cartao-box label {
        font-size: 0.95rem;
        color: #0d6efd;
        font-weight: 600;
        margin-bottom: 0.25rem;
        display: block;
    }
    .modal-editar-usuario .taxa-cartao-box input[type="text"] {
        text-align: center;
        border-radius: 1.5rem;
        border: 1px solid #ced4da;
        font-weight: 500;
        width: 70px;
        font-size: 0.98rem;
        margin: 0 auto;
        padding: 0.2rem 0.5rem;
    }
    @media (max-width: 768px) {
        .modal-editar-usuario .taxa-cartao-grid {
            gap: 0.5rem;
        }
        .modal-editar-usuario .taxa-cartao-box {
            width: 45vw;
            min-width: 90px;
        }
    }
    @media (max-width: 480px) {
        .modal-editar-usuario .taxa-cartao-box {
            width: 90vw;
        }
    }
</style>

<div class="modal fade modal-custom-height modal-editar-usuario" id="editModal-{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4 border-0">
                <h5 class="modal-title fw-bold" id="editModalLabel-{{ $user->id }}">
                    <i class="fa-solid fa-user-pen me-2"></i>Editar Usuário
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.usuarios.edit', ['id' => $user->id]) }}" class="needs-validation h-100 d-flex flex-column" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId" name="id">
                <div class="modal-body rounded-bottom-4 flex-grow-1">
                    <ul class="nav nav-tabs mb-4" id="userEditTab-{{ $user->id }}" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-dados-{{ $user->id }}" data-bs-toggle="tab" data-bs-target="#tab-content-dados-{{ $user->id }}" type="button" role="tab">Dados Pessoais</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-permissoes-{{ $user->id }}" data-bs-toggle="tab" data-bs-target="#tab-content-permissoes-{{ $user->id }}" type="button" role="tab">Permissões</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-gateways-{{ $user->id }}" data-bs-toggle="tab" data-bs-target="#tab-content-gateways-{{ $user->id }}" type="button" role="tab">Gateways</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-taxas-{{ $user->id }}" data-bs-toggle="tab" data-bs-target="#tab-content-taxas-{{ $user->id }}" type="button" role="tab">Taxas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-taxas-cartao-{{ $user->id }}" data-bs-toggle="tab" data-bs-target="#tab-content-taxas-cartao-{{ $user->id }}" type="button" role="tab">Taxas Cartão</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-chaves-{{ $user->id }}" data-bs-toggle="tab" data-bs-target="#tab-content-chaves-{{ $user->id }}" type="button" role="tab">Chaves de Acesso</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-documentos-{{ $user->id }}" data-bs-toggle="tab" data-bs-target="#tab-content-documentos-{{ $user->id }}" type="button" role="tab">Documentos</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="userEditTabContent-{{ $user->id }}">
                        <div class="tab-pane fade show active" id="tab-content-dados-{{ $user->id }}" role="tabpanel">
                            <div class="row g-4">
                                <div class="mb-3 col-md-6">
                                    <label for="editNome-{{ $user->id }}" class="form-label fw-semibold">Nome</label>
                                    <input type="text" value="{{ $user->name }}" class="form-control rounded-pill" id="editNome-{{ $user->id }}" name="name">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="editCpf-{{ $user->cpf_cnpj }}" class="form-label fw-semibold">CPF/CNPJ</label>
                                    <input type="text" value="{{ $user->cpf_cnpj }}" class="form-control rounded-pill" id="editCpf-{{ $user->cpf_cnpj }}" name="cpf_cnpj">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="editTelefone-{{ $user->telefone }}" class="form-label fw-semibold">Telefone</label>
                                    <input type="text" value="{{ $user->cpf_cnpj }}" class="form-control rounded-pill" id="editTelefone-{{ $user->telefone }}" name="telefone">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="editDn-{{ $user->data_nascimento }}" class="form-label fw-semibold">Data nascimento</label>
                                    <input type="date" value="{{ $user->data_nascimento }}" class="form-control rounded-pill" id="editDn-{{ $user->data_nascimento }}" name="data_nascimento">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="editEmail-{{ $user->id }}" class="form-label fw-semibold">Email</label>
                                    <input type="email" value="{{ $user->email }}" class="form-control rounded-pill" id="editEmail-{{ $user->id }}" name="email">
                                </div>
                            </div>
                            <div class="mt-4">
                                <h6 class="fw-bold text-primary mb-3">Plano Ativo</h6>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="card shadow-sm border-0" style="min-width: 220px; max-width: 250px;">
                                        <div class="card-body text-center">
                                            <span class="badge bg-primary mb-2"><i class="fa-solid fa-star me-1"></i>Básico</span>
                                            <div class="fw-semibold mb-1">Recursos essenciais para começar</div>
                                            <div class="text-muted small">Limite de transações: 100/mês<br>Suporte padrão</div>
                                        </div>
                                    </div>
                                    <div class="card shadow-sm border-0" style="min-width: 220px; max-width: 250px;">
                                        <div class="card-body text-center">
                                            <span class="badge bg-success mb-2"><i class="fa-solid fa-gem me-1"></i>Intermediário</span>
                                            <div class="fw-semibold mb-1">Mais recursos e flexibilidade</div>
                                            <div class="text-muted small">Limite de transações: 500/mês<br>Suporte prioritário</div>
                                        </div>
                                    </div>
                                    <div class="card shadow-sm border-0" style="min-width: 220px; max-width: 250px;">
                                        <div class="card-body text-center">
                                            <span class="badge bg-warning text-dark mb-2"><i class="fa-solid fa-crown me-1"></i>Avançado</span>
                                            <div class="fw-semibold mb-1">Para grandes volumes e empresas</div>
                                            <div class="text-muted small">Transações ilimitadas<br>Suporte dedicado</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-content-permissoes-{{ $user->id }}" role="tabpanel">
                            <div class="row g-4">
                                <div class="mb-3 col-md-3">
                                    <label for="editPermission-{{ $user->id }}" class="form-label fw-semibold">Permissão</label>
                                    <select
                                    onchange="onChangePermission(this, '{{$user->id}}')"
                                    class="form-select rounded-pill"
                                    value="{{ $user->permission }}"
                                    id="editPermission-{{ $user->id }}"
                                    data-permission-select
                                    data-permission-id="{{ $user->id }}"
                                    name="permission">
                                        <option value="1" {{ $user->permission == 1 ? "selected" : "" }}>Usuário</option>
                                        <option value="5" {{ $user->permission == 5 ? "selected" : "" }}>Gerente</option>
                                        <option value="3" {{ $user->permission == 3 ? "selected" : "" }}>Admin</option>
                                    </select>
                                </div>
                                <div class="hidden mb-3 col-md-3" id="container-percentage-{{$user->id}}">
                                    <label for="editPorcentagem-{{ $user->id }}" class="form-label fw-semibold">Porcentagem</label>
                                    <input type="text" value="{{ $user->gerente_percentage }}" class="form-control rounded-pill" id="editPorcentagem-{{ $user->id }}" id="gerente_percentage" name="gerente_percentage">
                                </div>
                                <div class="hidden mb-3 col-md-3" id="container-gerenteaprovar-{{$user->id}}">
                                    <div class="pt-4 form-check form-switch d-flex align-items-center">
                                        <input
                                            class="form-check-input custom-switch-lg"
                                            name="gerente_aprovar"
                                            type="checkbox"
                                            role="switch"
                                            id="switchCheckDefault"
                                            value="{{ $user->gerente_aprovar }}"
                                            {{ ($user->gerente_aprovar ?? false) ? 'checked' : '' }}>
                                        <label class="text-xl form-check-label ms-2 fw-semibold" for="switchCheckDefault">
                                            Pode aprovar
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-3" id="container-gerente-{{$user->id}}">
                                    <label for="editGerente-{{ $user->id }}" class="form-label fw-semibold">Gerente</label>
                                    <select class="form-select rounded-pill" id="editGerente-{{ $user->id }}" name="gerente_id">
                                        <option value="" {{ $user->gerente_id == NULL ? "selected" : "" }}>Nenhum</option>
                                        @foreach ($gerentes as $gerente)
                                            <option value="{{ $gerente->id }}" {{ $user->gerente_id == $gerente->id ? "selected" : "" }}>{{ $gerente->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-content-gateways-{{ $user->id }}" role="tabpanel">
                            <div class="row g-4">
                                <div class="mb-3 col-md-3">
                                    <label for="editGatewayCashIn-{{ $user->id }}" class="form-label fw-semibold">Gateway Depósito</label>
                                    <select
                                    onchange="onChangeGatewayCashIn(this, '{{$user->id}}')"
                                    class="form-select rounded-pill"
                                    value="{{ $user->gateway_cashin }}"
                                    id="editGatewayCashIn-{{ $user->id }}"
                                    data-gateway-select
                                    data-gateway-id="{{ $user->id }}"
                                    name="gateway_cashin">
                                        <option value="" {{ $user->gateway_cashin == NULL ? "selected" : "" }}>Nenhum</option>
                                        @foreach ($gateways as $gateway)
                                            <option value="{{ $gateway->adquirente }}" {{ $user->gateway_cashin == $gateway->adquirente ? "selected" : "" }}>{{ $gateway->adquirente }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="editGatewayCashIn-{{ $user->id }}" class="form-label fw-semibold">Gateway Saque</label>
                                    <select
                                    onchange="onChangeGatewayCashOut(this, '{{$user->id}}')"
                                    class="form-select rounded-pill"
                                    value="{{ $user->gateway_cashout }}"
                                    id="editGatewayCashOut-{{ $user->id }}"
                                    data-gateway-select
                                    data-gateway-id="{{ $user->id }}"
                                    name="gateway_cashout">
                                        <option value="" {{ $user->gateway_cashout == NULL ? "selected" : "" }}>Nenhum</option>
                                        @foreach ($gateways as $gateway)
                                            <option value="{{ $gateway->adquirente }}" {{ $user->gateway_cashout == $gateway->adquirente ? "selected" : "" }}>{{ $gateway->adquirente }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-content-taxas-{{ $user->id }}" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mt-2 mb-2">Taxas Gerais</h6>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="editTaxaCashIn-{{ $user->id }}" class="form-label fw-semibold">Taxa Depósito (%)</label>
                                    <input type="text" value="{{ $user->taxa_cash_in }}" class="form-control rounded-pill" id="editTaxaCashIn-{{ $user->id }}" name="taxa_cash_in">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="editTaxaCashOut-{{ $user->id }}" class="form-label fw-semibold">Taxa Saque (%)</label>
                                    <input type="text" value="{{ $user->taxa_cash_out }}" class="form-control rounded-pill" id="editTaxaCashOut-{{ $user->id }}" name="taxa_cash_out">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="editTaxaPercentual-{{ $user->id }}" class="form-label fw-semibold">Taxa Percentual (%)</label>
                                    <input type="text" value="{{ $user->taxa_percentual }}" class="form-control rounded-pill" id="editTaxaPercentual-{{ $user->id }}" name="taxa_percentual">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="editTaxaCashInFixa-{{ $user->id }}" class="form-label fw-semibold">Taxa Depósito (R$)</label>
                                    <input type="text" value="{{ $user->taxa_cash_in_fixa }}" class="form-control rounded-pill" id="editTaxaCashInFixa-{{ $user->id }}" name="taxa_cash_in_fixa">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="editTaxaCashOutFixa-{{ $user->id }}" class="form-label fw-semibold">Taxa Saque (R$)</label>
                                    <input type="text" value="{{ $user->taxa_cash_out_fixa }}" class="form-control rounded-pill" id="editTaxaCashOutFixa-{{ $user->id }}" name="taxa_cash_out_fixa">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="editTaxaCashInBase-{{ $user->id }}" class="form-label fw-semibold">Baseline Entrada (R$)</label>
                                    <input type="text" value="{{ $user->baseline_cash_in }}" class="form-control rounded-pill" id="editTaxaCashInBase-{{ $user->id }}" name="baseline_cash_in">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="editTaxaCashOutBase-{{ $user->id }}" class="form-label fw-semibold">Baseline Saída (R$)</label>
                                    <input type="text" value="{{ $user->baseline_cash_out }}" class="form-control rounded-pill" id="editTaxaCashOutBase-{{ $user->id }}" name="baseline_cash_out">
                                </div>
                                <div class="col-12"><hr></div>
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-2">Taxas Boleto</h6>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="editTaxaBoletoFixa-{{ $user->id }}" class="form-label fw-semibold">Taxa Fixa (R$)</label>
                                    <input type="text" value="{{ $user->taxa_boleto_fixa }}" class="form-control rounded-pill" id="editTaxaBoletoFixa-{{ $user->id }}" name="taxa_boleto_fixa">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="editTaxaBoletoPercentual-{{ $user->id }}" class="form-label fw-semibold">Taxa Percentual (%)</label>
                                    <input type="text" value="{{ $user->taxa_boleto_percentual }}" class="form-control rounded-pill" id="editTaxaBoletoPercentual-{{ $user->id }}" name="taxa_boleto_percentual">
                                </div>
                                <div class="col-12"><hr></div>
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-2">Taxas Produto Checkout</h6>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="editTaxaProdutoCheckoutFixa-{{ $user->id }}" class="form-label fw-semibold">Taxa Fixa (R$)</label>
                                    <input type="text" value="{{ $user->taxa_produto_checkout_fixa }}" class="form-control rounded-pill" id="editTaxaProdutoCheckoutFixa-{{ $user->id }}" name="taxa_produto_checkout_fixa">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="editTaxaProdutoCheckoutPercentual-{{ $user->id }}" class="form-label fw-semibold">Taxa Percentual (%)</label>
                                    <input type="text" value="{{ $user->taxa_produto_checkout_percentual }}" class="form-control rounded-pill" id="editTaxaProdutoCheckoutPercentual-{{ $user->id }}" name="taxa_produto_checkout_percentual">
                                </div>
                                <div class="col-12"><hr></div>
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-2">Modo de Taxa</h6>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="editTaxaModo-{{ $user->id }}" class="form-label fw-semibold">Modo de Taxa</label>
                                    <select name="tax_method" id="editTaxaModo-{{ $user->id }}" class="form-select rounded-pill">
                                        <option value="balance" {{ $user->tax_method === 'balance' ? 'selected' : '' }}>Descontar no Saldo</option>
                                        <option value="external" {{ $user->tax_method === 'external' ? 'selected' : '' }}>Descontar na Saída</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-content-taxas-cartao-{{ $user->id }}" role="tabpanel">
                            <div class="fw-bold text-primary text-center mb-3 mt-2">Taxas Cartão de Crédito por Parcela</div>
                            <div class="taxa-cartao-grid">
                                @for ($i = 1; $i <= 12; $i++)
                                <div class="taxa-cartao-box">
                                    <label>{{ $i }}x</label>
                                    <input type="text" class="form-control form-control-sm" id="editTaxaCartaoParcela-{{ $user->id }}-{{ $i }}" name="taxa_cartao_parcela[{{ $i }}]" value="{{ $user->taxas_cartao[$i] ?? '' }}" placeholder="%">
                                </div>
                                @endfor
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-content-chaves-{{ $user->id }}" role="tabpanel">
                            <div class="row g-4">
                                <div class="mb-3 col-md-6" id="container-token-{{$user->id}}">
                                    <label for="e-token-{{ $user->id }}" class="form-label fw-semibold">Token</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control rounded-start-pill" value="{{ $user->chaves->token ?? '' }}" name="token" id="e-token-{{ $user->id }}" readonly>
                                        <button class="btn btn-outline-primary rounded-end-pill" onclick="gerarChaveToken('{{ $user->id }}')" type="button"><i class="fa-solid fa-sync"></i></button>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6" id="container-secret-{{$user->id}}">
                                    <label for="e-secret-{{ $user->id }}" class="form-label fw-semibold">Secret</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control rounded-start-pill" value="{{ $user->chaves->secret ?? '' }}" name="secret" id="e-secret-{{ $user->id }}" readonly>
                                        <button class="btn btn-outline-primary rounded-end-pill" onclick="gerarChaveSecret('{{ $user->id }}')" type="button"><i class="fa-solid fa-sync"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-content-documentos-{{ $user->id }}" role="tabpanel">
                            <div class="text-end mt-2">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold mb-3" id="btn-toggle-fotos-{{ $user->id }}">
                                    <i class="fa-regular fa-image me-2"></i>Visualizar Fotos
                                </button>
                            </div>
                            <div class="row d-none" id="fotos-container-{{ $user->id }}">
                                <div class="mb-3 col-md-4">
                                    <x-image-upload id="foto_rg_frente" name="foto_rg_frente" label="Foto RG (Frente)" :value="'/'.$user->foto_rg_frente" />
                                </div>
                                <div class="mb-3 col-md-4">
                                    <x-image-upload id="foto_rg_verso" name="foto_rg_verso" label="Foto RG (Verso)" :value="'/'.$user->foto_rg_verso" />
                                </div>
                                <div class="mb-3 col-md-4">
                                    <x-image-upload id="selfie_rg" name="selfie_rg" label="Selfie RG" :value="'/'.$user->selfie_rg" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-center">
                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-bold">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modals = document.querySelectorAll('.modal');

    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function () {
            Inputmask({"mask": "(99) 99999-9999"}).mask(modal.querySelectorAll('input[name="telefone"]'));
            Inputmask({
                mask: ["999.999.999-99", "99.999.999/9999-99"],
                keepStatic: true
            }).mask(modal.querySelectorAll('input[name="cpf_cnpj"]'));
            Inputmask({"mask": "99999-999"}).mask(modal.querySelectorAll('input[name="cep"]'));
        });
    });

    // Executa onChangePermission para todos os campos já preenchidos
    document.querySelectorAll('[data-permission-select]').forEach(function(select) {
        const id = select.dataset.permissionId;
        onChangePermission(select, id);
    });

    // Toggle fotos
    document.querySelectorAll('[id^="btn-toggle-fotos-"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var userId = btn.id.replace('btn-toggle-fotos-', '');
            var fotosContainer = document.getElementById('fotos-container-' + userId);
            if (fotosContainer.classList.contains('d-none')) {
                fotosContainer.classList.remove('d-none');
                btn.innerHTML = '<i class="fa-regular fa-image me-2"></i>Ocultar Fotos';
            } else {
                fotosContainer.classList.add('d-none');
                btn.innerHTML = '<i class="fa-regular fa-image me-2"></i>Visualizar Fotos';
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Executa onChangePermission para todos os campos já preenchidos
    document.querySelectorAll('[data-permission-select]').forEach(function(select) {
        const id = select.dataset.permissionId;
        onChangePermission(select, id);
    });
});

function onChangePermission(input, id){
        console.log()
        const containerPercentage = document.getElementById(`container-percentage-${id}`);
        const containerToken = document.getElementById(`container-token-${id}`);
        const containerSecret = document.getElementById(`container-secret-${id}`);
        const containerGerente = document.getElementById(`container-gerente-${id}`);
        const containerGerenteAprovar = document.getElementById(`container-gerenteaprovar-${id}`);

        if(input.value === "5"){
            containerPercentage.classList.remove('hidden');
            containerGerenteAprovar.classList.remove('hidden');
            containerToken.classList.remove('col-md-6');
            containerToken.classList.add('col-md-3');
            containerSecret.classList.remove('col-md-6');
            containerSecret.classList.add('col-md-3');
            containerGerente.classList.remove('col-md-3');
            containerGerente.classList.add('col-md-3');
        } else {
            containerPercentage.classList.add('hidden');
            containerGerenteAprovar.classList.add('hidden');
            containerToken.classList.remove('col-md-3');
            containerToken.classList.add('col-md-6');
            containerSecret.classList.remove('col-md-3');
            containerSecret.classList.add('col-md-6');
            containerGerente.classList.remove('col-md-3');
            containerGerente.classList.add('col-md-3');
        }
};

// Toggle fotos
document.querySelectorAll('[id^="btn-toggle-fotos-"]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var userId = btn.id.replace('btn-toggle-fotos-', '');
        var fotosContainer = document.getElementById('fotos-container-' + userId);
        if (fotosContainer.classList.contains('d-none')) {
            fotosContainer.classList.remove('d-none');
            btn.innerHTML = '<i class="fa-regular fa-image me-2"></i>Ocultar Fotos';
        } else {
            fotosContainer.classList.add('d-none');
            btn.innerHTML = '<i class="fa-regular fa-image me-2"></i>Visualizar Fotos';
        }
    });
});
</script>
