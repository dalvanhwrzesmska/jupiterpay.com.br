@props([
    'user'
])

<style>
    .modal-visualizar-usuario .modal-content {
        min-height: 70vh;
        max-height: 90vh;
        overflow: hidden;
    }
    .modal-visualizar-usuario .modal-body {
        overflow-y: auto;
        max-height: 80vh;
        padding-bottom: 40px !important;
        background: transparent !important;
    }
    .modal-visualizar-usuario .section-title {
        font-weight: 600;
        color: #0d6efd;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    .modal-visualizar-usuario hr {
        margin: 1rem 0;
    }
</style>

<div class="modal fade modal-visualizar-usuario" id="visModal-{{ $user->id }}" tabindex="-1" aria-labelledby="visModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4 border-0">
                <h5 class="modal-title fw-bold" id="visModalLabel-{{ $user->id }}">
                    <i class="fa-solid fa-user me-2"></i>Visualizar Usuário
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body rounded-bottom-4 flex-grow-1">
                <div class="container-fluid">
                    <div class="section-title">Dados Pessoais</div>
                    <div class="row gy-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Usuário:</label>
                            <p>{{ $user->user_id }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Nome:</label>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">CPF:</label>
                            <p>{{ $user->cpf }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Data de Nascimento:</label>
                            <p>{{ $user->data_nascimento }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Data de Cadastro:</label>
                            <p>{{ \Carbon\Carbon::parse($user->data_cadastro)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Dados Empresariais</div>
                    <div class="row gy-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Razão Social:</label>
                            <p>{{ $user->razao_social }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Nome Fantasia:</label>
                            <p>{{ $user->nome_fantasia }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">CPF/CNPJ:</label>
                            <p>{{ $user->cpf_cnpj }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Contato</div>
                    <div class="row gy-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Email:</label>
                            <p>{{ $user->email }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Telefone:</label>
                            <p>{{ $user->telefone }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Status & Ações</div>
                    <div class="row gy-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Status:</label>
                            <p class="{{ $user->status == 0 ? 'bg-warning-transparent text-warning' : ($user->status == 1 ? 'bg-success-transparent text-success' : ($user->status == 3 ? 'bg-danger-transparent text-danger' : ($user->status == 5 ? 'bg-warning-transparent text-warning' : 'bg-secondary text-dark'))) }} p-2 rounded">
                                {{ $user->status == 0 ? 'Pendente' : ($user->status == 1 ? 'Aprovado' : ($user->status == 3 ? 'Banido' : ($user->status == 5 ? 'Aguardando aprovação' : 'Status Desconhecido'))) }}
                            </p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <form method="POST" action="{{ route('admin.usuarios.mudarstatus') }}">
                                <input id="id" name="id" value="{{$user->id}}" hidden />
                                <input id="tipo" name="tipo" value="status" hidden />
                                @csrf
                                @if ($user->status == 1)
                                <button type="submit" name="reavaliar" class="btn btn-warning w-100">Colocar em Análise</button>
                                @else
                                <button type="submit" name="aprovar" class="btn btn-success w-100">Aprovar Usuário</button>
                                @endif
                            </form>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <form method="POST" action="{{ route('admin.usuarios.mudarstatus') }}">
                                <input id="id" name="id" value="{{$user->id}}" hidden />
                                <input id="tipo" name="tipo" value="banido" hidden />
                                @csrf
                                @if ($user->banido == 0)
                                <button type="submit" name="banir" class="btn btn-warning w-100">Banir Usuário</button>
                                @else
                                <button type="submit" name="desbanir" class="btn btn-success w-100">Desbanir Usuário</button>
                                @endif
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Chaves de Acesso</div>
                    <div class="row gy-2">
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Token:</label>
                            <p>{{ $user->chaves->token ?? '' }}</p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Secret:</label>
                            <p>{{ $user->chaves->secret ?? '' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Documentos</div>
                    <div class="row gy-4">
                        <div class="text-center col-12 col-sm-6 col-md-4">
                            <img src="{{ asset($user->foto_rg_frente) }}" alt="Foto de Frente RG" class="img-thumbnail" width="150">
                        </div>
                        <div class="text-center col-12 col-sm-6 col-md-4">
                            <img src="{{ asset($user->foto_rg_verso) }}" alt="Foto de Verso RG" class="img-thumbnail" width="150">
                        </div>
                        <div class="text-center col-12 col-sm-6 col-md-4">
                            <img src="{{ asset($user->selfie_rg) }}" alt="Selfie RG" class="img-thumbnail" width="150">
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Permissões e Gerência</div>
                    <div class="row gy-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Permissão:</label>
                            <p>{{ $user->permission == 1 ? 'Usuário' : ($user->permission == 5 ? 'Gerente' : ($user->permission == 3 ? 'Admin' : 'Outro')) }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Porcentagem (Gerente):</label>
                            <p>{{ $user->gerente_percentage ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Pode aprovar:</label>
                            <p>{{ ($user->gerente_aprovar ?? false) ? 'Sim' : 'Não' }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Gerente:</label>
                            <p>{{ $user->gerente->name ?? '-' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Gateways</div>
                    <div class="row gy-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Gateway Depósito:</label>
                            <p>{{ $user->gateway_cashin ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Gateway Saque:</label>
                            <p>{{ $user->gateway_cashout ?? '-' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Taxas Gerais</div>
                    <div class="row gy-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Taxa Depósito:</label>
                            <p>{{ $user->taxa_cash_in ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Taxa Saque:</label>
                            <p>{{ $user->taxa_cash_out ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Taxa Percentual:</label>
                            <p>{{ $user->taxa_percentual ?? '-' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Taxas Boleto</div>
                    <div class="row gy-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Taxa Fixa (R$):</label>
                            <p>{{ $user->taxa_boleto_fixa ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Taxa Percentual (%):</label>
                            <p>{{ $user->taxa_boleto_percentual ?? '-' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Taxas Produto Checkout</div>
                    <div class="row gy-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Taxa Fixa (R$):</label>
                            <p>{{ $user->taxa_produto_checkout_fixa ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="form-label">Taxa Percentual (%):</label>
                            <p>{{ $user->taxa_produto_checkout_percentual ?? '-' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="section-title">Taxas Cartão de Crédito por Parcela</div>
                    <div class="row gy-2">
                        @for ($i = 1; $i <= 12; $i++)
                        <div class="col-6 col-sm-4 col-md-2 mb-2">
                            <div class="border rounded text-center p-2 bg-light">
                                <div class="fw-bold text-primary">{{ $i }}x</div>
                                <div>{{ $user->taxas_cartao[$i] ?? '-' }}%</div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
