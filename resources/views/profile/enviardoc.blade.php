<x-app-layout :route="'Validar documentos'">
    <div class="main-content app-content">
        <div class="container-fluid mt-5">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <div class="avatar avatar-lg bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fas fa-file-check text-primary fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark mb-1">Validação de Documentos</h4>
                            <p class="text-muted mb-0">Complete seus dados para validação da conta</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <form method="POST" enctype="multipart/form-data" action="{{ route('profile.enviardocs', ['id' => auth()->id() ]) }}">
                @csrf
                <div class="row g-4">
                    <!-- Dados Pessoais Card -->
                    <div class="col-12">
                        <div class="card card-raised border-0 shadow-lg">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="avatar avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-primary mb-1">Dados Pessoais</h5>
                                        <p class="text-muted small mb-0">Informações básicas do titular da conta</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="cpf-cnpj" class="form-label fw-semibold text-dark">
                                            <i class="fas fa-id-card me-2 text-primary"></i>CPF/CNPJ
                                        </label>
                                        <input type="text" class="form-control form-control-lg border-2" id="cpf-cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" required placeholder="Digite seu CPF ou CNPJ">
                                        @error('cpf_cnpj')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="data-nascimento" class="form-label fw-semibold text-dark">
                                            <i class="fas fa-calendar-alt me-2 text-primary"></i>Data de Nascimento
                                        </label>
                                        <input type="date" class="form-control form-control-lg border-2" id="data-nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}" required>
                                        @error('data_nascimento')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="media_faturamento" class="form-label fw-semibold text-dark">
                                            <i class="fas fa-chart-line me-2 text-primary"></i>Média de Faturamento Mensal
                                        </label>
                                        <select class="form-select form-select-lg border-2" name="media_faturamento" required>
                                            <option value="">Selecione uma opção</option>
                                            <option value="10000-30000" {{ old('media_faturamento') == '10000-30000' ? 'selected' : '' }}>Entre R$ 10.000 - 30.000</option>
                                            <option value="30000-100000" {{ old('media_faturamento') == '30000-100000' ? 'selected' : '' }}>Entre R$ 30.000 - 100.000</option>
                                            <option value="100000-400000" {{ old('media_faturamento') == '100000-400000' ? 'selected' : '' }}>Entre R$ 100.000 - 400.000</option>
                                            <option value="500000+" {{ old('media_faturamento') == '500000+' ? 'selected' : '' }}>Acima de R$ 500.000</option>
                                        </select>
                                        @error('media_faturamento')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço Card -->
                    <div class="col-12">
                        <div class="card card-raised border-0 shadow-lg">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="avatar avatar-sm bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-map-marker-alt text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-success mb-1">Endereço</h5>
                                        <p class="text-muted small mb-0">Informações do seu endereço residencial</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label for="CEP" class="form-label fw-semibold text-dark">
                                            <i class="fas fa-mail-bulk me-2 text-success"></i>CEP
                                        </label>
                                        <input type="text" class="form-control form-control-lg border-2" id="CEP" name="cep" value="{{ old('cep') }}" required placeholder="00000-000">
                                        @error('cep')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-8">
                                        <label for="rua" class="form-label fw-semibold text-dark">
                                            <i class="fas fa-road me-2 text-success"></i>Logradouro
                                        </label>
                                        <input type="text" class="form-control form-control-lg border-2" id="rua" name="rua" value="{{ old('rua') }}" required placeholder="Nome da rua">
                                        @error('rua')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="numero_residencia" class="form-label fw-semibold text-dark">
                                            <i class="fas fa-hashtag me-2 text-success"></i>Número
                                        </label>
                                        <input type="text" class="form-control form-control-lg border-2" id="numero_residencia" name="numero_residencia" value="{{ old('numero_residencia') }}" required placeholder="123">
                                        @error('numero_residencia')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-9">
                                        <label for="complemento" class="form-label fw-semibold text-dark">
                                            <i class="fas fa-building me-2 text-success"></i>Complemento
                                        </label>
                                        <input type="text" class="form-control form-control-lg border-2" id="complemento" name="complemento" value="{{ old('complemento') }}" placeholder="Apartamento, bloco, etc.">
                                        @error('complemento')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="bairro" class="form-label fw-semibold text-dark">
                                            <i class="fas fa-map me-2 text-success"></i>Bairro
                                        </label>
                                        <input type="text" class="form-control form-control-lg border-2" id="bairro" name="bairro" value="{{ old('bairro') }}" required placeholder="Nome do bairro">
                                        @error('bairro')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cidade" class="form-label fw-semibold text-dark">
                                            <i class="fas fa-city me-2 text-success"></i>Cidade
                                        </label>
                                        <input type="text" class="form-control form-control-lg border-2" id="cidade" name="cidade" value="{{ old('cidade') }}" required placeholder="Nome da cidade">
                                        @error('cidade')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="estado" class="form-label fw-semibold text-dark">
                                            <i class="fas fa-globe-americas me-2 text-success"></i>Estado
                                        </label>
                                        <input type="text" class="form-control form-control-lg border-2" id="estado" name="estado" value="{{ old('estado') }}" required placeholder="UF">
                                        @error('estado')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos Card -->
                    <div class="col-12">
                        <div class="card card-raised border-0 shadow-lg">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="avatar avatar-sm bg-info-subtle rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-camera text-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-info mb-1">Documentos</h5>
                                        <p class="text-muted small mb-0">Envie fotos dos seus documentos para validação</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <div class="alert alert-info border-0 bg-info-subtle">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle text-info me-2"></i>
                                        <div>
                                            <strong>Dicas importantes:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Tire fotos em boa iluminação</li>
                                                <li>Certifique-se que o documento está legível</li>
                                                <li>Formatos aceitos: JPG, PNG, JPEG</li>
                                                <li>Tamanho máximo: 5MB por arquivo</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="upload-card h-100">
                                            <label for="foto_rg_frente" class="form-label fw-semibold text-dark mb-3">
                                                <i class="fas fa-id-card me-2 text-info"></i>RG/CNH - Frente
                                            </label>
                                            <div class="upload-area border-2 border-dashed border-secondary rounded-3 p-4 text-center position-relative">
                                                <input class="form-control position-absolute opacity-0" type="file" accept="image/*" id="foto_rg_frente" name="foto_rg_frente" required style="width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;">
                                                <div class="upload-content">
                                                    <i class="fas fa-cloud-upload-alt text-secondary fs-1 mb-3"></i>
                                                    <h6 class="fw-bold text-dark mb-2">Clique para enviar</h6>
                                                    <p class="text-muted small mb-0">Foto da frente do documento</p>
                                                </div>
                                            </div>
                                            @error('foto_rg_frente')
                                                <div class="invalid-feedback d-block mt-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="upload-card h-100">
                                            <label for="foto_rg_verso" class="form-label fw-semibold text-dark mb-3">
                                                <i class="fas fa-id-card me-2 text-info"></i>RG/CNH - Verso
                                            </label>
                                            <div class="upload-area border-2 border-dashed border-secondary rounded-3 p-4 text-center position-relative">
                                                <input class="form-control position-absolute opacity-0" type="file" accept="image/*" id="foto_rg_verso" name="foto_rg_verso" required style="width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;">
                                                <div class="upload-content">
                                                    <i class="fas fa-cloud-upload-alt text-secondary fs-1 mb-3"></i>
                                                    <h6 class="fw-bold text-dark mb-2">Clique para enviar</h6>
                                                    <p class="text-muted small mb-0">Foto do verso do documento</p>
                                                </div>
                                            </div>
                                            @error('foto_rg_verso')
                                                <div class="invalid-feedback d-block mt-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="upload-card h-100">
                                            <label for="selfie_rg" class="form-label fw-semibold text-dark mb-3">
                                                <i class="fas fa-user-circle me-2 text-info"></i>Selfie com RG/CNH
                                            </label>
                                            <div class="upload-area border-2 border-dashed border-secondary rounded-3 p-4 text-center position-relative">
                                                <input class="form-control position-absolute opacity-0" type="file" accept="image/*" id="selfie_rg" name="selfie_rg" required style="width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;">
                                                <div class="upload-content">
                                                    <i class="fas fa-cloud-upload-alt text-secondary fs-1 mb-3"></i>
                                                    <h6 class="fw-bold text-dark mb-2">Clique para enviar</h6>
                                                    <p class="text-muted small mb-0">Selfie segurando o documento</p>
                                                </div>
                                            </div>
                                            @error('selfie_rg')
                                                <div class="invalid-feedback d-block mt-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <div class="card card-raised border-0 shadow-lg">
                            <div class="card-body text-center py-4">
                                <div class="mb-3">
                                    <i class="fas fa-shield-alt text-success fs-1 mb-3"></i>
                                    <h5 class="fw-bold text-dark mb-2">Finalizar Validação</h5>
                                    <p class="text-muted mb-0">Seus dados serão analisados em até 24 horas</p>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar Documentos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .card.card-raised {
            border-radius: 1rem !important;
            transition: all 0.3s ease;
        }

        .card.card-raised:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12) !important;
        }

        .form-control, .form-select {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
            transform: translateY(-1px);
        }

        .form-label.fw-semibold {
            color: #374151 !important;
            margin-bottom: 0.75rem;
        }

        .upload-area {
            min-height: 180px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            transform: translateY(-2px);
        }

        .upload-card {
            transition: all 0.3s ease;
        }

        .avatar {
            width: 2.5rem;
            height: 2.5rem;
        }

        .avatar.avatar-lg {
            width: 4rem;
            height: 4rem;
        }

        .alert {
            border-radius: 0.75rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 100%);
            transform: translateY(-2px);
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 0.875rem;
        }

        /* Dark mode support */
        .dark .card.card-raised,
        [data-bs-theme="dark"] .card.card-raised,
        body.dark .card.card-raised {
            background-color: #1f2937 !important;
            color: #e5e7eb !important;
            border-color: #374151 !important;
        }

        .dark .form-label.fw-semibold,
        [data-bs-theme="dark"] .form-label.fw-semibold,
        body.dark .form-label.fw-semibold {
            color: #e5e7eb !important;
        }

        .dark .upload-area,
        [data-bs-theme="dark"] .upload-area,
        body.dark .upload-area {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
        }

        .dark .upload-area:hover,
        [data-bs-theme="dark"] .upload-area:hover,
        body.dark .upload-area:hover {
            background: linear-gradient(135deg, #4b5563 0%, #6b7280 100%);
        }
    </style>

    <!-- Enhanced Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // CEP API functionality
            document.getElementById("CEP").addEventListener("blur", function() {
                let cep = this.value.replace(/\D/g, "");
                
                if (cep.length === 8) {
                    // Show loading state
                    this.classList.add("loading");
                    
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                document.getElementById("rua").value = data.logradouro || "";
                                document.getElementById("bairro").value = data.bairro || "";
                                document.getElementById("cidade").value = data.localidade || "";
                                document.getElementById("estado").value = data.uf || "";
                                
                                // Add success animation
                                animateSuccess(document.getElementById("CEP"));
                            } else {
                                showToast("CEP não encontrado!", "error");
                                limparCampos();
                            }
                        })
                        .catch(error => {
                            console.error("Erro ao buscar o CEP:", error);
                            showToast("Erro ao buscar o CEP", "error");
                            limparCampos();
                        })
                        .finally(() => {
                            this.classList.remove("loading");
                        });
                }
            });

            // File upload preview functionality
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    const uploadArea = this.closest('.upload-area');
                    const uploadContent = uploadArea.querySelector('.upload-content');
                    
                    if (file) {
                        // Show file preview
                        uploadContent.innerHTML = `
                            <i class="fas fa-check-circle text-success fs-1 mb-3"></i>
                            <h6 class="fw-bold text-success mb-2">Arquivo Selecionado</h6>
                            <p class="text-muted small mb-0">${file.name}</p>
                        `;
                        uploadArea.classList.add('border-success');
                        uploadArea.classList.remove('border-secondary');
                        
                        // Add success animation
                        animateSuccess(uploadArea);
                    }
                });
            });

            // Form validation enhancement
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-hourglass-half me-2"></i>Enviando...';
                submitBtn.disabled = true;
            });

            function limparCampos() {
                document.getElementById("rua").value = "";
                document.getElementById("bairro").value = "";
                document.getElementById("cidade").value = "";
                document.getElementById("estado").value = "";
            }

            function animateSuccess(element) {
                element.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                }, 200);
            }

            function showToast(message, type = 'info') {
                // Simple toast notification
                const toast = document.createElement('div');
                toast.className = `alert alert-${type === 'error' ? 'danger' : 'success'} position-fixed top-0 end-0 m-3`;
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                    <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
                    ${message}
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }

            // CPF/CNPJ mask
            const cpfCnpjInput = document.getElementById('cpf-cnpj');
            cpfCnpjInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    // CPF mask
                    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                } else {
                    // CNPJ mask
                    value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                }
                this.value = value;
            });

            // CEP mask
            const cepInput = document.getElementById('CEP');
            cepInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
                this.value = value;
            });
        });
    </script>

    <!-- Enhanced Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header border-0 text-center pb-0">
                    <div class="w-100">
                        <div class="mb-3">
                            <div class="avatar avatar-lg bg-success-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                <i class="fas fa-check-circle text-success fs-1"></i>
                            </div>
                            <h4 class="modal-title fw-bold text-success" id="successModalLabel">Documentos Enviados!</h4>
                        </div>
                    </div>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mb-4">
                        <p class="text-muted mb-3">Seus dados foram enviados com sucesso e estão em análise.</p>
                        <div class="alert alert-info border-0 bg-info-subtle">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-clock text-info me-2"></i>
                                <span class="fw-semibold">Você receberá um retorno em até 24 horas</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center pt-0">
                    <button type="button" class="btn btn-primary btn-lg px-4 rounded-pill" onclick="window.location.href='../home';">
                        <i class="fas fa-home me-2"></i>Ir para Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
