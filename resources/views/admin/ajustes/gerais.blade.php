<x-app-layout :route="'[ADMIN] Ajustes Gerais'">
    <style>
        .ajustes-bg {
            /*background: linear-gradient(120deg, #f8fafc 0%, #e0e7ef 100%);*/
            min-height: 100vh;
            padding-top: 24px;
        }
        .ajustes-header {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(80,120,180,0.09);
            padding: 2.1rem 2.2rem 2.2rem 2.2rem;
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        .ajustes-header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #27272a;
            margin-bottom: 0;
            letter-spacing: -0.5px;
        }
        .ajustes-card {
            border-radius: 20px;
            /*background: linear-gradient(100deg, #f1f5f9 70%, #e0e7ef 100%);*/
            box-shadow: 0 6px 24px 0 rgba(80,120,180,0.14);
            border: none;
        }
        .ajustes-card .card-body {
            padding: 2.2rem 2rem 2.1rem 2rem;
        }
        .form-label {
            font-weight: 600;
            color: #3b4252;
            margin-bottom: .45rem;
        }
        .form-control,
        .form-select {
            border-radius: 12px;
            border: 1px solid #e0e7ef;
            box-shadow: 0 1px 7px 0 rgba(60,90,190,0.04);
            transition: border-color .17s, box-shadow .17s;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 .13rem rgba(129,140,248,.13);
        }
        .modern-tabs {
            border-bottom: none;
            margin-bottom: 2rem;
            display: flex;
            gap: 0.5rem;
            background: #f8fafc;
            /*border-radius: 1.5rem;*/
            padding: 0.3rem 0.6rem;
            box-shadow: 0 2px 14px 0 rgba(60,90,190,0.06);
        }
        .modern-tabs .modern-tab {
            border: none;
            background: none;
            color: #686c73;
            font-weight: 700;
            font-size: 1.08rem;
            padding: 0.85rem 2.1rem;
            border-radius: 1.2rem;
            transition: color .16s, background .16s;
            cursor: pointer;
        }
        .modern-tabs .modern-tab.active,
        .modern-tabs .modern-tab:focus {
            background: linear-gradient(90deg, #6d28d9 0%, #38bdf8 100%);
            color: #fff;
            box-shadow: 0 2px 12px 0 rgba(100,80,250,0.12);
        }
        .tab-section {
            display: none;
        }
        .tab-section.active {
            display: block;
            animation: fadeIn .26s;
        }
        @keyframes fadeIn {
          from { opacity: 0; transform: translateY(12px);}
          to { opacity: 1; transform: none;}
        }
        .section-divider {
            border-bottom: 1px solid #e5e7eb;
            margin: 1.5rem 0 2rem 0;
        }
        .btn-gradient-primary {
            background: linear-gradient(90deg, #6d28d9 0%, #38bdf8 100%);
            color: #fff;
            border: none;
            border-radius: 13px;
            box-shadow: 0 2px 10px 0 rgba(100,80,250,0.13);
            font-weight: 600;
            padding: 0.7rem 2.3rem;
            font-size: 1.11rem;
            transition: background .16s, box-shadow .16s;
        }
        .btn-gradient-primary:hover, .btn-gradient-primary:focus {
            background: linear-gradient(90deg, #38bdf8 0%, #6d28d9 100%);
            color: #fff;
            box-shadow: 0 4px 18px 0 rgba(100,80,250,0.18);
        }
        .x-image-upload-label {
            font-weight: 600;
            color: #64748b;
        }
        .x-image-upload-preview {
            border-radius: 16px;
            box-shadow: 0 1.5px 8px rgba(60,90,190,0.08);
        }
        @media (max-width: 900px) {
            .ajustes-header { flex-direction: column; align-items: flex-start; padding: 1.2rem; }
            .modern-tabs { flex-direction: column; gap: 0.2rem; padding: 0.2rem; }
            .modern-tabs .modern-tab { width: 100%; text-align: left; }
        }
    </style>
    <div class="main-content app-content ajustes-bg">
        <div class="container-fluid">
            <div class="ajustes-header mb-4">
                <h1 class="mb-0 display-5"><i class="fa-solid fa-sliders me-2 text-primary"></i> Ajustes gerais</h1>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="ajustes-card card">
                        <div class="card-body">
                            <div class="modern-tabs" id="ajustesTabs">
                                <button type="button" class="modern-tab active" data-tab="financeiro"><i class="fa-solid fa-coins me-2"></i>Financeiro</button>
                                <button type="button" class="modern-tab" data-tab="empresa"><i class="fa-solid fa-building me-2"></i>Empresa</button>
                                <button type="button" class="modern-tab" data-tab="personalizacao"><i class="fa-solid fa-palette me-2"></i>Personalização</button>
                            </div>
                            <form method="POST" action="{{ route('admin.ajustes.gerais') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div id="tab-financeiro" class="tab-section active">
                                    <div class="row g-3">
                                        <div class="col-xl-3">
                                            <label for="taxa_cash_in_padrao" class="form-label">Taxa PIX IN (%)</label>
                                            <input type="text" class="form-control @error('taxa_cash_in_padrao') is-invalid @enderror" name="taxa_cash_in_padrao" value="{{ $setting->taxa_cash_in_padrao }}" required>
                                            @error('taxa_cash_in_padrao')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="taxa_cash_out_padrao" class="form-label">Taxa PIX OUT (%)</label>
                                            <input type="text" class="form-control @error('taxa_cash_out_padrao') is-invalid @enderror" name="taxa_cash_out_padrao" value="{{ $setting->taxa_cash_out_padrao }}" required>
                                            @error('taxa_cash_out_padrao')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="taxa_fixa_padrao" class="form-label">Taxa Fixa (R$)</label>
                                            <input type="text" class="form-control @error('taxa_fixa_padrao') is-invalid @enderror" name="taxa_fixa_padrao" value="{{ $setting->taxa_fixa_padrao }}" required>
                                            @error('taxa_fixa_padrao')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="baseline" class="form-label">Taxa Baseline (R$)</label>
                                            <input type="text" class="form-control @error('baseline') is-invalid @enderror" name="baseline" value="{{ $setting->baseline }}" required>
                                            @error('baseline')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="deposito_minimo" class="form-label">Depósito Mínimo</label>
                                            <input type="text" class="form-control @error('deposito_minimo') is-invalid @enderror" name="deposito_minimo" value="{{ $setting->deposito_minimo }}" required>
                                            @error('deposito_minimo')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="saque_minimo" class="form-label">Saque Mínimo</label>
                                            <input type="text" class="form-control @error('saque_minimo') is-invalid @enderror" name="saque_minimo" value="{{ $setting->saque_minimo }}" required>
                                            @error('saque_minimo')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="limite_saque_mensal" class="form-label">Limite Mensal (P.F)</label>
                                            <input type="text" class="form-control @error('limite_saque_mensal') is-invalid @enderror" name="limite_saque_mensal" value="{{ $setting->limite_saque_mensal }}" required>
                                            @error('limite_saque_mensal')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-empresa" class="tab-section">
                                    <div class="row g-3">
                                        <div class="col-xl-6">
                                            <label for="gateway_name" class="form-label">Nome do Gateway</label>
                                            <input type="text" class="form-control @error('gateway_name') is-invalid @enderror" name="gateway_name" value="{{ $setting->gateway_name }}" required>
                                            @error('gateway_name')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="cnpj" class="form-label">CNPJ</label>
                                            <input type="text" class="form-control @error('cnpj') is-invalid @enderror" name="cnpj" value="{{ $setting->cnpj }}" required>
                                            @error('cnpj')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="contato" class="form-label">Contato (Gerente)</label>
                                            <input type="text" class="form-control @error('contato') is-invalid @enderror" name="contato" value="{{ $setting->contato }}" required>
                                            @error('contato')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-personalizacao" class="tab-section">
                                    <div class="row g-3">
                                        <div class="col-xl-6">
                                            <label for="gateway_color" class="form-label">Cor padrão</label>
                                            <input type="color" style="height:42px;" class="form-control @error('gateway_color') is-invalid @enderror" name="gateway_color" value="{{ $setting->gateway_color }}" required>
                                            @error('gateway_color')
                                            <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <x-image-upload id="gateway_logo" name="gateway_logo" label="Logo" :value="asset($setting->gateway_logo)" />
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <x-image-upload id="gateway_favicon" name="gateway_favicon" label="Ícone" :value="asset($setting->gateway_favicon)" />
                                        </div>
                                        <div class="col-12">
                                            <x-image-upload id="gateway_banner_home" name="gateway_banner_home" label="Banner Dashboard" :value="asset($setting->gateway_banner_home)" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 text-end mt-4">
                                    <button type="submit" class="btn btn-gradient-primary">
                                        <i class="fa-solid fa-floppy-disk me-2"></i> Salvar ajustes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Navegação moderna das tabs sem Bootstrap JS
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.modern-tab');
            const sections = document.querySelectorAll('.tab-section');
            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    tabs.forEach(t => t.classList.remove('active'));
                    sections.forEach(s => s.classList.remove('active'));
                    tab.classList.add('active');
                    document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
                });
            });
        });
    </script>
</x-app-layout>