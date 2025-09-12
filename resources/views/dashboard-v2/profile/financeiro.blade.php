@extends('dashboard-v2.layout')

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-3 md-mb-0 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5"></h1>
                </div>
            </div>

            <!-- Start::page-header -->
            <div class="flex-wrap gap-2 d-flex align-items-center justify-content-between page-header-breadcrumb">
            </div>
            <!-- End::page-header -->

            <!-- Start:: row-1 -->
            <div class="mb-3 row">
                <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                    <div class="card card-raised highlight-card financeiro-page-card-raised financeiro-page-highlight-card h-100">
                        <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="display-6 fw-bold text-warning">R$ {{ number_format(auth()->user()->saldo + auth()->user()->valor_saque_pendente ?? 0, 2, ',', '.') }}</div>
                                <div class="text-muted">Disponível + Pendente</div>
                            </div>
                            <div class="financeiro-page-icon-circle bg-success text-white">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="2" y="7" width="20" height="13" rx="3" fill="#198754"/>
                                    <rect x="2" y="7" width="20" height="13" rx="3" stroke="#fff" stroke-width="1.5"/>
                                    <rect x="2" y="4" width="20" height="5" rx="2" fill="#fff"/>
                                    <rect x="2" y="4" width="20" height="5" rx="2" stroke="#fff" stroke-width="1.5"/>
                                    <circle cx="18" cy="15" r="1.5" fill="#fff"/>
                                </svg>
                            </div>
                        </div>
                        <div class="card-body pt-0 pb-3 px-4">
                            <span class="badge bg-warning text-dark"><i class="fa-brands fa-pix"></i> Total</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card card-raised highlight-card financeiro-page-card-raised financeiro-page-highlight-card h-100">
                        <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="display-6 fw-bold text-success">R$ {{ number_format(auth()->user()->saldo ?? 0, 2, ',', '.') }}</div>
                                <div class="text-muted">Disponível para saque</div>
                            </div>
                            <div class="financeiro-page-icon-circle bg-success text-white">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="2" y="7" width="20" height="13" rx="3" fill="#198754"/>
                                    <rect x="2" y="7" width="20" height="13" rx="3" stroke="#fff" stroke-width="1.5"/>
                                    <rect x="2" y="4" width="20" height="5" rx="2" fill="#fff"/>
                                    <rect x="2" y="4" width="20" height="5" rx="2" stroke="#fff" stroke-width="1.5"/>
                                    <circle cx="18" cy="15" r="1.5" fill="#fff"/>
                                </svg>
                            </div>
                        </div>
                        <div class="card-body pt-0 pb-3 px-4">
                            <span class="badge bg-success"><i class="fa-brands fa-pix"></i> Liberado</span>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Start::row-1 -->
            <div class="row">
                <div class="mb-3 col-xl-6 md-mb-0 ">
                    <div class="card card-raised highlight-card financeiro-page-card-raised financeiro-page-highlight-card">
                        <div class="p-0 card-body">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <button class="btn btn-primary rounded-pill py-3 px-4 d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addsaldo">
                                    <i class="align-middle ri-add-circle-line fs-16 me-1"></i> Adicionar Saldo
                                </button>

                                <!-- Modal -->
                                <div class="modal fade financeiro-modal-modern" id="addsaldo" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="mail-ComposeLabel">Adicionar Saldo</h6>
                                                <button id="btnDepositar" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="depositForm" method="POST">
                                            @csrf
                                                <div class="px-4 modal-body">
                                                    <div class="row gy-3">
                                                        <div class="col-12">
                                                            <label for="valor" class="form-label">Valor</label>
                                                            <input type="number" step="0.01" class="form-control" id="valor_deposito" name="valor" placeholder="Valor" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                    <button id="btn-depositar" type="submit" class="btn btn-primary">Depositar</button>
                                                </div>
                                            </form>
                                            <div id="data-qrcode" class="qrcode-section" style="width:100%;display: none;">
                                                <img id="pix-qr-code" width="200" height="200" class="mb-2" style="background:#fff;border-radius:12px;box-shadow:0 2px 12px 0 rgba(60,80,180,0.10);" />
                                                <input id="pix-copia-e-cola" style="background: #f6f8fa; width: 80%;border-radius:1.2rem;" class="mb-2 form-control text-center" readonly />
                                                <button class="btn btn-primary mt-2" style="border-radius:1.5rem;" onclick="copiarTexto()">Copiar chave</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Explicação sobre taxas padrão -->
                            <div class="m-3 alert alert-info">
                                <ul>
                                    <li><strong>Taxa de depósito:</strong> {{ number_format($taxas['taxa_cash_in'], 2, '.', '') }}% + {{ 'R$ '.number_format($taxas['taxa_cash_in_fixa'], 2, ',', '.') }}</li>
                                    <li><strong>Limite Pessoa física:</strong> Sem limite</li>
                                    <li><strong>Limite Pessoa jurídica:</strong> Sem limite</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card card-raised highlight-card financeiro-page-card-raised financeiro-page-highlight-card">
                        <div class="p-0 card-body">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <button class="btn btn-primary rounded-pill py-3 px-4 d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addtask"
                                    data-saldo="{{ $saldoliquido }}">
                                    <i class="align-middle ri-add-circle-line fs-16 me-1"></i> Solicitar saque
                                </button>

                                <!-- Modal -->
                                <div class="modal fade financeiro-modal-modern" id="addtask" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="mail-ComposeLabel">Novo Saque</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="saqueForm" method="POST">
                                            @csrf
                                                <div class="px-4 modal-body">
                                                    <div class="row gy-3">

                                                        <!-- Verificação de saldo baixo -->
                                                        @if($saldoBaixo)
                                                        <div class="mt-4 alert alert-danger">
                                                            <strong>Saldo muito baixo para realizar um saque.</strong>
                                                        </div>
                                                        @endif

                                                        @if($retiradasPendentes)
                                                        <div class="mt-4 alert alert-warning">
                                                            <strong>Já existe um saque em processamento. Aguarde a conclusão.</strong>
                                                        </div>
                                                        @endif

                                                        <input type="hidden" id="nonce" name="nonce" value="{{ bin2hex(random_bytes(16)) }}">

                                                        <!-- Exibição do saldo disponível -->
                                                        <div class="mt-4 alert alert-info">
                                                            <ul>
                                                                <li><strong>DISPONÍVEL PARA SAQUE:</strong> R$: {{ number_format(auth()->user()->saldo, 2, ',', '.') }}</li>
                                                            </ul>
                                                        </div>

                                                        <!-- Campo de valor -->
                                                        <div class="col-xl-12">
                                                            <label for="valor" class="form-label">Valor</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                id="valor"
                                                                max="{{ auth()->user()->saldo }}"
                                                                name="valor"
                                                                placeholder="Valor"
                                                                required>
                                                            <!-- <div id="valorLiquido" class="mt-2 text-success"></div> -->
                                                            <div id="containerValorLiquido" style="display: none;" class="mt-4 alert alert-success">
                                                                <ul>
                                                                    <li><strong id="valorLiquido"></strong></li>
                                                                </ul>
                                                            </div>
                                                            <div id="valorError" class="mt-2 text-danger" style="display: none;">Saldo insuficiente para o valor solicitado.</div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                        <label class="form-label">Tipo de Chave</label>
                                                            <select id="tipo_chave" name="tipo_chave" type="text" class="form-control">
                                                                <option value="cpf">CPF</option>
                                                                <option value="cnpj">CNPJ</option>
                                                                <option value="email">EMAIL</option>
                                                                <option value="phone">CELULAR</option>
                                                                <option value="random">ALEATÓRIA</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <label for="chave" class="form-label">Chave PIX:</label>
                                                            <input type="text" class="form-control" id="chave" name="chave" placeholder="Chave" required>
                                                        </div>

                                                        <!-- Campo oculto para o ID do usuário -->
                                                        <input type="hidden" id="user_id" name="user_id" value="{{ $email }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                    <button id="btnSolicitarSaque" type="submit" class="btn btn-primary" {{ $retiradasPendentes >= 1 ? 'disabled' : '' }}>Solicitar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Explicação sobre taxas padrão -->
                            <div class="m-3 alert alert-info">
                                <ul>
                                    <li><strong>Taxa de saque:</strong> {{ number_format($taxas['taxa_cash_out'], 2, '.', '') }}% + {{ 'R$ '.number_format($taxas['taxa_cash_out_fixa'], 2, ',', '.') }}</li>
                                    @if(isset($setting->limite_saque_mensal) && (float)$setting->limite_saque_mensal > 0)
                                        <li><strong>Limite Pessoa física:</strong> R$ {{ number_format($setting->limite_saque_mensal, '2', ',', '.') }} /mês</li>
                                    @else
                                        <li><strong>Limite Pessoa física:</strong> Sem limite</li>
                                    @endif
                                      <li><strong>Limite Pessoa jurídica:</strong> Sem limite</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->
        </div>
    </div>

    <script>
        function copiarTexto() {
            var input = document.getElementById("pix-copia-e-cola");
            input.select();
            document.execCommand("copy");
            showToast("success","Chave Pix copiada!");
        }
    </script>

    <script>
        document.getElementById('depositForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let btnDepositar = document.getElementById('btn-depositar');
            btnDepositar.setAttribute('disabled', true);
            var paymentCode;
            var transactionId;
            generateQRCode();
            async function generateQRCode() {
                var name = "{{ auth()->user()->name }}";
                var cpf = "{{ auth()->user()->cpf_cnpj }}";
                var email = "{{ auth()->user()->email }}";
                var amount = document.getElementById('valor_deposito').value;
                var apiUrl = "{{ env('APP_URL') }}/api/wallet/deposit/payment";
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var phone = "{{ auth()->user()->telefone }}";
                var payload = {
                    "token": token,
                    "secret": secret,
                    "amount": parseFloat(amount),
                    "debtor_name": name,
                    "email": email,
                    "debtor_document_number": cpf,
                    "phone": phone,
                    "method_pay": "pix",
                    "postback": "web",
                    "nonce": document.getElementById('nonce').value
                };
                try {
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();
                    console.log('resposta', data);
                    if (data.qrcode) {
                        paymentCode = data.qrcode;
                        paymentCodeBase64 = data.qr_code_image_url;
                        transactionId = data.idTransaction; // Ajustado para pegar idTransaction



                        // Adiciona o paymentCode ao texto da div
                        document.getElementById('pix-qr-code').src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(paymentCode);
                        document.getElementById('pix-copia-e-cola').value = paymentCode;
                        let pixcontainer = document.getElementById('data-qrcode');
                        pixcontainer.style.display = 'flex';
                        pixcontainer.style.flexDirection = "column";
                        pixcontainer.style.alignItems = "center";
                        pixcontainer.style.justifyContent = "center";
                        pixcontainer.style.gap = 5;
                        document.getElementById('depositForm').style.display = 'none';

                        // Inicia a verificação do pagamento a cada 2 segundos
                        setInterval(checkPaymentStatus, 5000);
                    } else {
                        btnDepositar.setAttribute('disabled', false);
                        console.error("Erro na solicitação:", data.message);
                    }
                } catch (error) {
                    btnDepositar.setAttribute('disabled', false);
                    console.error("Erro na solicitação:", error);
                }
            }

            async function checkPaymentStatus() {
                var apiUrl = "{{env('APP_URL')}}/api/status";
                var payload = {
                    "idTransaction": transactionId
                };

                try {
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (data.status === "PAID_OUT") {
                        clearInterval(checkPaymentStatus); // Para a verificação quando o pagamento for confirmado

                        showToast('success', "Saldo adcionado com sucesso!")
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000)
                    } else if (data.status === "WAITING_FOR_APPROVAL") {
                        console.log("Aguardando aprovação...");
                    }
                } catch (error) {
                    console.error("Erro na verificação do pagamento:", error);
                }
            }
        })
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let btnSolicitarSaque = document.getElementById("btnSolicitarSaque");
            let inputValor = document.getElementById("valor");
            let inputChave = document.getElementById("chave");
            let valorLiquidoInput = document.getElementById("valorLiquido");
            let containerValorLiquido = document.getElementById("containerValorLiquido");

            // Desabilita o botão inicialmente
            btnSolicitarSaque.setAttribute("disabled", true);

            function validarCampos() {
                let valorPreenchido = inputValor.value && parseFloat(inputValor.value) > 0;
                let chavePreenchida = inputChave.value.trim().length > 0;

                if (valorPreenchido && chavePreenchida) {
                    btnSolicitarSaque.removeAttribute("disabled");
                } else {
                    btnSolicitarSaque.setAttribute("disabled", true);
                }
            }

            function calcularValorLiquido() {
                let maxValue = parseFloat(inputValor.max) || 0;
                let currentValue = parseFloat(inputValor.value) || 0;

                if (currentValue > maxValue) {
                    inputValor.value = maxValue;
                    currentValue = maxValue;
                }

                if (currentValue <= 0 || isNaN(currentValue)) {
                    containerValorLiquido.style.display = "none";
                } else {
                    containerValorLiquido.style.display = "block";
                }

                let tx_cash_out = parseFloat("{{ $taxas['taxa_cash_out'] }}") || 0;
                let taxa_fixa_padrao = parseFloat("{{ $taxas['taxa_cash_out_fixa'] }}") || 0;
                let valorLiquido = (currentValue - taxa_fixa_padrao) * (1 - tx_cash_out / 100);

                valorLiquidoInput.innerText = "Valor líquido a receber: " +
                    valorLiquido.toLocaleString("pt-BR", {
                        style: "currency",
                        currency: "BRL"
                    });
            }

            inputValor.addEventListener("input", function() {
                calcularValorLiquido();
                validarCampos();
            });

            inputChave.addEventListener("input", validarCampos);
        });
    </script>

    <script>
        document.getElementById('saqueForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var saldo = "{{ $saldoliquido }}"; // Corrigido para usar PHP para obter o saldo
            var valor = parseFloat(document.getElementById('valor').value);
            var valorError = document.getElementById('valorError');

            // Verifica se o saldo é zero ou se o valor solicitado é maior que o saldo
            if (saldo <= 0) {
                showToast('warning', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
            } else if (valor > saldo) {
                showToast('success', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
            }
            
            let btnSolicitarSaque = document.getElementById("btnSolicitarSaque");
            btnSolicitarSaque.setAttribute("disabled", true);


            requestPayment();
            async function requestPayment() {
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var amount = document.getElementById('valor').value;
                var pixKey = document.getElementById('chave').value;
                var pixKeyType = document.getElementById('tipo_chave').value;
                var apiUrl = "{{env('APP_URL')}}/api/pixout";

                if(parseFloat(valor) > parseFloat(saldo)){
                    valor = saldo;
                }

                var payload = {
                   token,
                   secret,
                   amount,
                   pixKey,
                   pixKeyType,
                   baasPostbackUrl: 'web'
                }

                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.id) {
                    try{
                        showToast('success', "Saque solicitado com sucesso.")
                    }catch(e){
                        console.log(e);
                    }
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000)
                } else {
                    showToast('warning', data.message);
                }
                
                btnSolicitarSaque.removeAttribute("disabled");
            }



        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
.financeiro-modal-modern .modal-footer {
    border-top: none;
    padding-top: 0.5rem;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}
.financeiro-modal-modern .modal-body {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.financeiro-modal-modern .qrcode-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.2rem;
    margin: 2rem 0 1.2rem 0;
    padding-bottom: 0.5rem;
}
    </style>
@endsection
