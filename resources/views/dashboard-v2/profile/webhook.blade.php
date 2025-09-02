@extends('dashboard-v2.layout')

@section('content')

    <style>
        .custom-select {
            position: relative;
            width: 100%;
            height: 39px !important;
        }

        .select-display {
            border: 1px solid #ccc;
            padding: 7px 10px 7.5px;
            cursor: pointer;
            background-color: #fff;
            border-radius: 4px;
        }

        .select-options {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            border: 1px solid #ccc;
            background-color: #fff;
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
            border-radius: 4px;
        }

        .select-options label {
            display: block;
            padding: 8px 10px;
            cursor: pointer;
        }

        .select-options label:hover {
            background-color: #f0f0f0;
        }

        .custom-select.open .select-options {
            display: block;
        }

        .tags {
            display: inline-block;
            background-color: var(--color-gateway, #007bff);
            color: white;
            border-radius: 3px;
            padding: 1px 6px;
            margin-right: 4px;
            font-size: 0.9em;
        }

        .webhook-modern-row {
            display: flex;
            gap: 2.5rem;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .webhook-modern-form-col {
            flex: 1 1 0;
            min-width: 380px;
            max-width: 650px;
        }

        .webhook-modern-example-col {
            flex: 1 1 0;
            min-width: 380px;
            max-width: 700px;
        }

        .webhook-modern-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 5px 24px 0 rgba(60, 80, 180, 0.1), 0 1.5px 3px 0 rgba(0, 0, 0, 0.04);
            border: none;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .webhook-modern-form .form-control,
        .webhook-modern-form .custom-select,
        .webhook-modern-form input[type="text"] {
            border-radius: 1.5rem;
            font-size: 1.08rem;
            /*padding: 0.7rem 1.2rem;*/
            box-shadow: 0 1px 6px 0 rgba(60, 140, 231, 0.06);
            border: 1px solid #e3e8ee;
        }

        .webhook-modern-form .btn {
            border-radius: 1.5rem !important;
            font-size: 1.08rem;
            padding: 0.7rem 1.2rem;
            font-weight: 600;
            box-shadow: 0 2px 8px 0 rgba(60, 80, 180, 0.08);
        }

        .webhook-modern-form .btn-success {
            background: #10b981;
            border: none;
        }

        .webhook-modern-form .btn-success:hover {
            background: #059669;
        }

        .webhook-modern-form .custom-select {
            position: relative;
            width: 100%;
            min-height: 40px;
            height: auto !important;
            margin-bottom: 0;
            background: none;
            box-shadow: none;
            border: none;
        }

        .webhook-modern-form .select-display {
            border-radius: 1.2rem;
            border: 1px solid #e3e8ee;
            box-shadow: 0 1px 6px 0 rgba(60, 140, 231, 0.06);
            padding: 0.5rem 1.1rem;
            font-size: 1.04rem;
            background: #fff;
            min-height: 40px;
            cursor: pointer;
            transition: border 0.15s;
        }

        .webhook-modern-form .select-options {
            border-radius: 1.1rem;
            border: 1px solid #e3e8ee;
            box-shadow: 0 2px 8px 0 rgba(60, 140, 231, 0.06);
            background: #fff;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 10;
            display: none;
            margin-top: 2px;
            padding: 0.3rem 0;
        }

        .webhook-modern-form .custom-select.open .select-options {
            display: block;
        }

        .webhook-modern-form .select-options label {
            display: block;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 1.01rem;
            border-radius: 0.7rem;
            margin-bottom: 2px;
        }

        .webhook-modern-form .select-options label:hover {
            background: #f1f5fd;
        }

        .webhook-modern-form .tags {
            background: #3c8ce7;
            color: #fff;
            border-radius: 1.2rem;
            padding: 0.18em 0.9em;
            font-size: 0.97rem;
            font-weight: 600;
            margin-right: 0.3em;
        }

        .webhook-json-box {
            background: #f6f8fa;
            border: 1.5px solid #e3e8ee;
            border-radius: 1.2rem;
            padding: 1.1rem 2.2rem 1.1rem 2.2rem;
            margin-bottom: 1.1rem;
            font-size: 1.08rem;
            position: relative;
            width: 100%;
        }

        .webhook-copy-btn {
            position: absolute;
            top: 10px;
            right: 14px;
            background: #e3e8ee;
            border: none;
            color: #3c8ce7;
            border-radius: 8px;
            padding: 5px 13px;
            font-size: 1.01rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 0.4em;
        }

        .webhook-copy-btn:hover {
            background: #3c8ce7;
            color: #fff;
        }

        #webhook-copy-toast {
            display: none;
            position: fixed;
            left: 50%;
            bottom: 32px;
            transform: translateX(-50%);
            background: #222;
            color: #fff;
            padding: 0.7rem 1.5rem;
            border-radius: 2rem;
            font-size: 1.05rem;
            box-shadow: 0 2px 12px 0 rgba(60, 80, 180, 0.13);
            opacity: 0.95;
            z-index: 9999;
        }

        /* Dark mode para tela de Webhook */
        .dark .webhook-modern-card,
        [data-bs-theme="dark"] .webhook-modern-card,
        body.dark .webhook-modern-card {
          background: #23272f !important;
          color: #e2e6ef !important;
          border-color: #232837 !important;
          box-shadow: 0 5px 24px 0 rgba(0,0,0,0.18), 0 1.5px 3px 0 rgba(0,0,0,0.08);
        }
        .dark .webhook-modern-form .form-control,
        [data-bs-theme="dark"] .webhook-modern-form .form-control,
        body.dark .webhook-modern-form .form-control,
        .dark .webhook-modern-form .select-display,
        [data-bs-theme="dark"] .webhook-modern-form .select-display,
        body.dark .webhook-modern-form .select-display {
          background: #181c24 !important;
          color: #e2e6ef !important;
          border-color: #232837 !important;
        }
        .dark .webhook-modern-form .select-options,
        [data-bs-theme="dark"] .webhook-modern-form .select-options,
        body.dark .webhook-modern-form .select-options {
          background: #23272f !important;
          color: #e2e6ef !important;
          border-color: #232837 !important;
        }
        .dark .webhook-modern-form .select-options label,
        [data-bs-theme="dark"] .webhook-modern-form .select-options label,
        body.dark .webhook-modern-form .select-options label {
          color: #e2e6ef !important;
        }
        .dark .webhook-modern-form .select-options label:hover,
        [data-bs-theme="dark"] .webhook-modern-form .select-options label:hover,
        body.dark .webhook-modern-form .select-options label:hover {
          background: #181c24 !important;
        }
        .dark .webhook-modern-form .btn,
        [data-bs-theme="dark"] .webhook-modern-form .btn,
        body.dark .webhook-modern-form .btn {
          background: #232837 !important;
          color: #e2e6ef !important;
          border: none;
        }
        .dark .webhook-modern-form .btn-success,
        [data-bs-theme="dark"] .webhook-modern-form .btn-success,
        body.dark .webhook-modern-form .btn-success {
          background: #10b981 !important;
          color: #fff !important;
        }
        .dark .webhook-modern-form .btn-success:hover,
        [data-bs-theme="dark"] .webhook-modern-form .btn-success:hover,
        body.dark .webhook-modern-form .btn-success:hover {
          background: #059669 !important;
        }
        .dark .webhook-json-box,
        [data-bs-theme="dark"] .webhook-json-box,
        body.dark .webhook-json-box {
          background: #181c24 !important;
          color: #e2e6ef !important;
          border-color: #232837 !important;
        }
        .dark .webhook-copy-btn,
        [data-bs-theme="dark"] .webhook-copy-btn,
        body.dark .webhook-copy-btn {
          background: #232837 !important;
          color: #3c8ce7 !important;
          border: none;
        }
        .dark .webhook-copy-btn:hover,
        [data-bs-theme="dark"] .webhook-copy-btn:hover,
        body.dark .webhook-copy-btn:hover {
          background: #3c8ce7 !important;
          color: #fff !important;
        }
        .dark #webhook-copy-toast,
        [data-bs-theme="dark"] #webhook-copy-toast,
        body.dark #webhook-copy-toast {
          background: #232837 !important;
          color: #fff !important;
        }

        @media (max-width: 900px) {
            .webhook-modern-row {
                flex-direction: column;
                gap: 1.5rem;
                align-items: stretch;
                max-width: 100%;
            }

            .webhook-modern-form-col,
            .webhook-modern-example-col {
                max-width: 100%;
                min-width: 0;
            }

            .webhook-json-box {
                padding: 1.1rem 1.1rem;
            }
        }
    </style>

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="flex-wrap gap-2 my-4 d-flex align-items-center justify-content-between page-header-breadcrumb">
                <div class="mb-2 md-mb-0 col col-12 col-lg-8 text-start">
                    <h1 class="display-5">Webhooks</h1>
                </div>
            </div>
            <div class="webhook-modern-row">
                <div class="webhook-modern-form-col">
                    <div class="webhook-modern-card p-4">
                        <form class="webhook-modern-form" method="POST" action="{{ route('webhook.update') }}">
                            @csrf
                            <div class="mb-4 form-group">
                                <label for="webhook_url" class="form-label">Webhook URL</label>
                                <input type="text" id="webhook_url" name="webhook_url"
                                    value="{{ auth()->user()->webhook_url }}" class="form-control input-shadow"
                                    placeholder="Digite a URL do webhook" required>
                            </div>
                            <div class="mb-4 form-group">
                                <label class="form-label">Eventos</label>
                                <div class="custom-select" id="multiSelect">
                                    <div class="select-display">Selecione os eventos</div>
                                    <div class="select-options">
                                        <label><input type="checkbox" value="gerado"> Pix Gerado</label>
                                        <label><input type="checkbox" value="pago"> Pix Pago</label>
                                    </div>
                                </div>
                                <input type="hidden" name="webhook_endpoint" id="webhook_endpoint" value="">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success w-100">Salvar Webhook</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="webhook-modern-example-col">
                    <div class="webhook-modern-card p-4" style="line-height:21px">
                        <h5 class="mb-3 fw-bold">Exemplo de Payload Recebido</h5>
                        <div class="webhook-json-box">
                            <button class="webhook-copy-btn" onclick="copyWebhookJson()"><i class="fa fa-copy"></i>
                                Copiar</button>
                            <pre id="webhook-json-recebido"
                                style="margin-bottom:0;background:transparent;border:none;box-shadow:none;padding:0;">{
      "nome": "João Silva",
      "cpf": "123.456.789-00",
      "email": "joao@email.com",
      "status": "pago"
    }</pre>
                        </div>
                        <div class="mt-3 text-muted" style="font-size:0.98rem;">
                            <span class="badge bg-info text-dark">POST</span> O webhook receberá uma requisição com os dados
                            acima.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Passa os eventos selecionados para o JavaScript --}}
    <script>
        const initialSelected = @json(auth()->user()->webhook_endpoint ?? []);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('multiSelect');
            const display = select.querySelector('.select-display');
            const options = select.querySelectorAll('.select-options input[type="checkbox"]');
            const hiddenInput = document.getElementById('webhook_endpoint');

            function updateDisplayAndInput() {
                const selected = Array.from(options)
                    .filter(opt => opt.checked)
                    .map(opt => opt.value);

                display.innerHTML = selected.length
                    ? selected.map(val => `<span class="tags">${val}</span>`).join('')
                    : 'Selecione os eventos';

                hiddenInput.value = JSON.stringify(selected);
            }

            // Inicializar estado baseado no backend (Laravel)
            options.forEach(opt => {
                if (initialSelected.includes(opt.value)) {
                    opt.checked = true;
                }
            });

            updateDisplayAndInput(); // Exibe visual inicial e atualiza hidden input

            // Toggle dropdown
            display.addEventListener('click', () => {
                select.classList.toggle('open');
            });

            // Atualiza estado ao marcar/desmarcar
            options.forEach(option => {
                option.addEventListener('change', updateDisplayAndInput);
            });

            // Fecha se clicar fora
            document.addEventListener('click', (e) => {
                if (!select.contains(e.target)) {
                    select.classList.remove('open');
                }
            });
        });
    </script>
    <div id="webhook-copy-toast">Copiado!</div>
    <script>
        function copyWebhookJson() {
            var el = document.getElementById('webhook-json-recebido');
            if (el) {
                var text = el.innerText || el.textContent;
                navigator.clipboard.writeText(text);
                var toast = document.getElementById('webhook-copy-toast');
                toast.innerText = 'Copiado!';
                toast.style.display = 'block';
                toast.style.opacity = '0.95';
                setTimeout(function () { toast.style.opacity = '0'; }, 1200);
                setTimeout(function () { toast.style.display = 'none'; }, 1500);
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

@endsection