<x-app-layout :route="'[ADMIN] Ajustes de adquirentes'">
    <div class="main-content app-content">
        <div class="container-fluid">
            <!-- Start::page-header -->
            <div class="mb-4 row align-items-center">
                <div class="col-12 col-md-6">
                    <h1 class="mb-0 display-5">Ajuste de Adquirentes</h1>
                </div>
            </div>

            <!-- Seção: Seleção de Adquirentes Default -->
            <div class="row mb-5">
                <div class="col-12 col-lg-12">
                    <div class="card card-raised shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="card-title mb-0">Adquirentes Padrão</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.default') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <label for="adquirente_deposito" class="form-label">Adquirente Depósito</label>
                                        <select class="form-select @error('adquirente_deposito') is-invalid @enderror" name="adquirente_deposito" required>
                                            <option value="">Selecione um adquirente</option>
                                            @foreach ($adquirentes as $adquirente)
                                                <option value="{{ $adquirente->adquirente }}" {{ $settings->gateway_cashin_default == $adquirente->adquirente ? 'selected' : '' }}>
                                                    {{ $adquirente->adquirente }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('adquirente_deposito')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="adquirente_saque" class="form-label">Adquirente Saque</label>
                                        <select class="form-select @error('adquirente_saque') is-invalid @enderror" name="adquirente_saque" required>
                                            <option value="">Selecione um adquirente</option>
                                            @foreach ($adquirentes as $adquirente)
                                                <option value="{{ $adquirente->adquirente }}" {{ $settings->gateway_cashout_default == $adquirente->adquirente ? 'selected' : '' }}>
                                                    {{ $adquirente->adquirente }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('adquirente_saque')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 text-end mt-3">
                                        <button type="submit" class="btn btn-primary px-4">Salvar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção: Cashtime -->
            <div class="row mb-5">
                <div class="col-12 col-lg-12">
                    <div class="card card-raised shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="card-title mb-0">Cashtime</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.cashtime') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-3">
                                    <div class="col-md-4">
                                        <label for="secret" class="form-label">Chave Secreta</label>
                                        <input type="text" class="form-control @error('secret') is-invalid @enderror" name="secret" value="{{ $cashtime->secret }}" required>
                                        @error('secret')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                        <input type="number" step="0.01" class="form-control @error('taxa_pix_cash_in') is-invalid @enderror" name="taxa_pix_cash_in" value="{{ $cashtime->taxa_pix_cash_in }}" required>
                                        @error('taxa_pix_cash_in')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                        <input type="number" step="0.01" class="form-control @error('taxa_pix_cash_out') is-invalid @enderror" name="taxa_pix_cash_out" value="{{ $cashtime->taxa_pix_cash_out }}" required>
                                        @error('taxa_pix_cash_out')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 text-end mt-3">
                                        <button type="submit" class="btn btn-primary px-4">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção: JupiterPay -->
            <div class="row mb-5">
                <div class="col-12 col-lg-12">
                    <div class="card card-raised shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="card-title mb-0">JupiterPay</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.jupiterpay') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-3">
                                    <div class="col-md-4">
                                        <label for="secret" class="form-label">Chave Secreta</label>
                                        <input type="text" class="form-control @error('secret') is-invalid @enderror" name="secret" value="{{ $cashtime->secret }}" required>
                                        @error('secret')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                        <input type="number" step="0.01" class="form-control @error('taxa_pix_cash_in') is-invalid @enderror" name="taxa_pix_cash_in" value="{{ $cashtime->taxa_pix_cash_in }}" required>
                                        @error('taxa_pix_cash_in')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                        <input type="number" step="0.01" class="form-control @error('taxa_pix_cash_out') is-invalid @enderror" name="taxa_pix_cash_out" value="{{ $cashtime->taxa_pix_cash_out }}" required>
                                        @error('taxa_pix_cash_out')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 text-end mt-3">
                                        <button type="submit" class="btn btn-primary px-4">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>