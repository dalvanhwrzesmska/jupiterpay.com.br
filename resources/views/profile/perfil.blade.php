<x-app-layout :route="'Meu Perfil'">
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Avatar Card -->
                <div class="col-xxl-3 col-md-6 mb-4">
                    <div class="card card-raised border-0 shadow-lg h-100 d-flex flex-column align-items-center justify-content-center">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                            <div style="display:flex;align-items:center;justify-content:center;width: 170px;height:170px;border-radius:50%;border: 2px dashed #e6e6e6; padding:20px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                                <form id="avatarForm" action="{{ route('profile.avatar.upload') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;" onchange="submitAvatarForm()">
                                    <div onclick="document.getElementById('avatarInput').click()" style="cursor: pointer; display:flex; align-items:center; justify-content:center; width: 160px; height:160px; border-radius:50%; border: 1px dashed #e6e6e6; padding:0.1rem; overflow: hidden; background: #fff;">
                                        <img src="{{ auth()->user()->avatar }}" style="width: 160px; height:160px; object-fit: cover; border-radius:50%;" title="Clique para alterar">
                                        <div style="position:absolute; width:100%; height:100%; left:0; top:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.16); opacity:0; transition:opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                                            <span class="text-white fw-bold" style="font-size: 1.1rem;">Alterar</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <p class="text-center text-muted small" style="margin-top:-18px">Permitido: <span class="fw-semibold">*.jpeg, *.jpg, *.png, *.gif</span></p>
                        <p class="mb-4 text-center text-muted small">Tamanho máximo: <span class="fw-semibold">3.1 MB</span></p>
                    </div>
                </div>
                <!-- User Info Card -->
                <div class="col-xxl-9 col-md-6 mb-4">
                    <div class="card card-raised border-0 shadow-lg h-100">
                        <div class="card-body px-4 py-4">
                            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-person-circle me-2"></i>Informações do Perfil</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Nome</label>
                                    <input disabled type="text" value="{{ auth()->user()->name }}" class="form-control-plaintext border-bottom" id="name" name="name">
                                </div>
                                <div class="col-md-6">
                                    <label for="cpf_cnpj" class="form-label fw-semibold">CPF/CNPJ</label>
                                    <input disabled type="text" value="{{ auth()->user()->cpf_cnpj }}" class="form-control-plaintext border-bottom" id="cpf_cnpj" name="cpf_cnpj">
                                </div>
                                <div class="col-md-3">
                                    <label for="data_nascimento" class="form-label fw-semibold">Data Nascimento</label>
                                    <input disabled type="text" value="{{ \Carbon\Carbon::parse(auth()->user()->data_nascimento)->format('d/m/Y') }}" class="form-control-plaintext border-bottom" id="data_nascimento" name="data_nascimento">
                                </div>
                                <div class="col-md-4">
                                    <label for="telefone" class="form-label fw-semibold">Telefone</label>
                                    <input disabled type="text" value="{{ auth()->user()->telefone }}" class="form-control-plaintext border-bottom" id="telefone" name="telefone">
                                </div>
                                <div class="col-md-5">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input disabled type="text" value="{{ auth()->user()->email }}" class="form-control-plaintext border-bottom" id="email" name="email">
                                </div>
                                <div class="col-md-3">
                                    <label for="cep" class="form-label fw-semibold">CEP</label>
                                    <input disabled type="text" value="{{ auth()->user()->cep }}" class="form-control-plaintext border-bottom" id="cep" name="cep">
                                </div>
                                <div class="col-md-9">
                                    <label for="rua" class="form-label fw-semibold">Logradouro</label>
                                    <input disabled type="text" value="{{ auth()->user()->rua }}" class="form-control-plaintext border-bottom" id="rua" name="rua">
                                </div>
                                <div class="col-md-3">
                                    <label for="numero_residencia" class="form-label fw-semibold">Número</label>
                                    <input disabled type="text" value="{{ auth()->user()->numero_residencia ?? "S/N" }}" class="form-control-plaintext border-bottom" id="numero_residencia" name="numero_residencia">
                                </div>
                                <div class="col-md-9">
                                    <label for="complemento" class="form-label fw-semibold">Complemento</label>
                                    <input disabled type="text" value="{{ auth()->user()->complemento }}" class="form-control-plaintext border-bottom" id="complemento" name="complemento">
                                </div>
                                <div class="col-md-4">
                                    <label for="bairro" class="form-label fw-semibold">Bairro</label>
                                    <input disabled type="text" value="{{ auth()->user()->bairro }}" class="form-control-plaintext border-bottom" id="bairro" name="bairro">
                                </div>
                                <div class="col-md-4">
                                    <label for="cidade" class="form-label fw-semibold">Cidade</label>
                                    <input disabled type="text" value="{{ auth()->user()->cidade }}" class="form-control-plaintext border-bottom" id="cidade" name="cidade">
                                </div>
                                <div class="col-md-4">
                                    <label for="estado" class="form-label fw-semibold">Estado</label>
                                    <input disabled type="text" value="{{ auth()->user()->estado }}" class="form-control-plaintext border-bottom" id="estado" name="estado">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .form-control-plaintext[disabled], .form-control[readonly] {
                background: transparent !important;
                color: #333 !important;
                font-weight: 500;
                border: none;
                border-bottom: 1px solid #e0e0e0 !important;
                border-radius: 0;
                padding-left: 0;
            }
            .card.card-raised {
                border-radius: 1rem !important;
            }
            .form-label.fw-semibold {
                color: #4b5563;
            }
            @media (max-width: 767.98px) {
                .row.g-4 { gap: 1rem !important; }
                .card.card-raised { margin-bottom: 1.5rem; }
            }
        </style>

        <script>
            function submitAvatarForm() {
                const form = document.getElementById('avatarForm');
                const input = document.getElementById('avatarInput');
                if (input.files.length > 0) {
                    form.submit();
                }
            }
        </script>
    </div>
</x-app-layout>