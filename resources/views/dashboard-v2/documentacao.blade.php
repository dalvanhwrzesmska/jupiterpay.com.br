@extends('dashboard-v2.layout')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.css" rel="stylesheet" />

    <style>
      h1, h2, h4 {
        color: #1d1d1f;
      }

      .method {
        background: #10b981;
        border-radius: 6px;
        padding: 4px 10px;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
        text-transform: uppercase;
      }

      code {
        font-family: 'Fira Code', 'Courier New', monospace;
        font-size: 1rem;
      }

      pre {
        overflow-x: auto;
        padding: 1rem;
        border-radius: 10px;
        background-color: #1e1e1e;
        color: #e0e0e0;
      }

      .endpoint-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
      }

      .endpoint-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .endpoint-section {
        margin-top: 2rem;
      }

      @media (max-width: 768px) {
        .endpoint-card {
          padding: 1.5rem;
        }

        .endpoint-title {
          font-size: 1.2rem;
        }

        h1 {
          font-size: 1.5rem;
        }

        code {
          font-size: 0.95rem;
        }
      }
      .icon-doc {
        color: var(--color-gateway) !important;
      }
      .endpoint-url {
        color: var(--color-gateway) !important;
        font-weight: bold;
        border: 1px solid var(--color-gateway) !important;
        padding: 5px;
        padding-right: 15px;
        border-radius: 8px;
      }
      .copy-btn {
        background: #f1f5fd;
        border: none;
        color: #3c8ce7;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 1.05rem;
        font-weight: 600;
        margin-left: 8px;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 0.4em;
      }
      .copy-btn:hover {
        background: #3c8ce7;
        color: #fff;
      }
      .copy-btn .fa-copy { font-size: 1.1em; }
      #copy-toast {
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
        box-shadow: 0 2px 12px 0 rgba(60,80,180,0.13);
        opacity: 0.95;
        z-index: 9999;
      }

      /* Dark mode para documentação */
      .dark .endpoint-card,
      [data-bs-theme="dark"] .endpoint-card,
      body.dark .endpoint-card {
        background: #23272f !important;
        border-color: #232837 !important;
        color: #e2e6ef !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.18);
      }
      .dark .main-content,
      [data-bs-theme="dark"] .main-content,
      body.dark .main-content {
        background: #181c24 !important;
        color: #e2e6ef !important;
      }
      .dark h1, .dark h2, .dark h4,
      [data-bs-theme="dark"] h1, [data-bs-theme="dark"] h2, [data-bs-theme="dark"] h4,
      body.dark h1, body.dark h2, body.dark h4 {
        color: #e2e6ef !important;
      }
      .dark .endpoint-title,
      [data-bs-theme="dark"] .endpoint-title,
      body.dark .endpoint-title {
        color: #fff !important;
      }
      .dark .endpoint-url,
      [data-bs-theme="dark"] .endpoint-url,
      body.dark .endpoint-url {
        color: #3c8ce7 !important;
        background: #232837 !important;
        border-color: #3c8ce7 !important;
      }
      .dark .copy-btn,
      [data-bs-theme="dark"] .copy-btn,
      body.dark .copy-btn {
        background: #232837 !important;
        color: #3c8ce7 !important;
        border: none;
      }
      .dark .copy-btn:hover,
      [data-bs-theme="dark"] .copy-btn:hover,
      body.dark .copy-btn:hover {
        background: #3c8ce7 !important;
        color: #fff !important;
      }
      .dark pre,
      [data-bs-theme="dark"] pre,
      body.dark pre {
        background: #181c24 !important;
        color: #e2e6ef !important;
      }
      .dark #copy-toast,
      [data-bs-theme="dark"] #copy-toast,
      body.dark #copy-toast {
        background: #232837 !important;
        color: #fff !important;
      }
    </style>

    <div class="main-content app-content mt-5">
      <div class="px-3 container-fluid px-md-5">
        <div class="mb-5">
          <h1 class="display-5"><i class="icon-doc fa-solid fa-file"></i>&nbsp;Documentação da API PIX</h1>
          <p class="text-muted">Endpoints para Depósito e Saque via PIX com Webhooks.</p>
        </div>

        <!-- PIX IN -->
        <div class="endpoint-card">
          <div class="endpoint-title"><i class="icon-doc fa-solid fa-download"></i> Depósito (PIX IN) <span class="method">POST</span></div>
          <p>Gera um pagamento via QrCode para depósito.</p>
          <p><strong>Endpoint:</strong> <code class="endpoint-url" id="endpoint-in">{{ env('APP_URL') }}/api/wallet/deposit/payment</code>
            <button class="copy-btn" onclick="copyDocText('endpoint-in')"><i class="fa fa-copy"></i> Copiar</button>
          </p>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-lock"></i>&nbsp; Cabeçalhos (Headers)</h4>
            <div style="position:relative;">
              <button class="copy-btn" style="position:absolute;top:10px;right:10px;z-index:2;" onclick="copyDocPre(this)"><i class="fa fa-copy"></i></button>
              <pre class="line-numbers"><code class="language-json">{
    "Content-Type": "application/json",
    "Accept": "application/json"
  }</code></pre>
            </div>
          </div>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-upload"></i>&nbsp; Corpo da Requisição</h4>
            <div style="position:relative;">
              <button class="copy-btn" style="position:absolute;top:10px;right:10px;z-index:2;" onclick="copyDocPre(this)"><i class="fa fa-copy"></i></button>
              <pre class="line-numbers"><code class="language-json">{
    "token": "seu_token",
    "secret": "seu_secret",
    "postback": "rota_callback",
    "amount": 100.00,
    "debtor_name": "Nome",
    "email": "email@dominio.com",
    "debtor_document_number": "CPF",
    "phone": "Telefone",
    "method_pay": "pix"
  }</code></pre>
            </div>
          </div>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-download"></i>&nbsp; Resposta</h4>
            <div style="position:relative;">
              <button class="copy-btn" style="position:absolute;top:10px;right:10px;z-index:2;" onclick="copyDocPre(this)"><i class="fa fa-copy"></i></button>
              <pre class="line-numbers"><code class="language-json">{
    "idTransaction": "TX123",
    "qrcode": "código copia e cola",
    "qr_code_image_url": "url da imagem"
  }</code></pre>
            </div>
          </div>
        </div>

        <!-- Webhook PIX IN -->
        <div class="endpoint-card">
          <div class="endpoint-title"><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Webhook PIX IN</div>
          <p>Retorno automático na rota <code>postback</code> informada na criação do depósito.</p>

          <h4><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Exemplo de retorno</h4>
          <div style="position:relative;">
            <button class="copy-btn" style="position:absolute;top:10px;right:10px;z-index:2;" onclick="copyDocPre(this)"><i class="fa fa-copy"></i></button>
            <pre class="line-numbers"><code class="language-json">{
    "status": "paid",
    "idTransaction": "TX123",
    "typeTransaction": "PIX"
  }</code></pre>
          </div>
        </div>

        <!-- PIX OUT -->
        <div class="endpoint-card">
          <div class="endpoint-title"><i class="icon-doc fa-solid fa-upload"></i>&nbsp; Saque (PIX OUT) <span class="method">POST</span></div>
          <p>Realiza um saque para uma chave PIX.</p>
          <p><strong>Endpoint:</strong> <code class="endpoint-url" id="endpoint-out">{{ env('APP_URL') }}/api/pixout</code>
            <button class="copy-btn" onclick="copyDocText('endpoint-out')"><i class="fa fa-copy"></i> Copiar</button>
          </p>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-lock"></i>&nbsp; Cabeçalhos (Headers)</h4>
            <div style="position:relative;">
              <button class="copy-btn" style="position:absolute;top:10px;right:10px;z-index:2;" onclick="copyDocPre(this)"><i class="fa fa-copy"></i></button>
              <pre class="line-numbers"><code class="language-json">{
    "Content-Type": "application/json",
    "Accept": "application/json"
  }</code></pre>
            </div>
          </div>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-upload"></i>&nbsp; Corpo da Requisição</h4>
            <p><strong>pixKeyType:</strong> 'cpf' | 'cnpj' | 'email' | 'phone' | 'random'</p>
            <div style="position:relative;">
              <button class="copy-btn" style="position:absolute;top:10px;right:10px;z-index:2;" onclick="copyDocPre(this)"><i class="fa fa-copy"></i></button>
              <pre class="line-numbers"><code class="language-json">{
    "token": "seu_token",
    "secret": "seu_secret",
    "baasPostbackUrl": "url_callback",
    "amount": 100.00,
    "pixKey": "chave_pix",
    "pixKeyType": "cpf"
  }</code></pre>
            </div>
          </div>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-download"></i>&nbsp; Resposta</h4>
            <div style="position:relative;">
              <button class="copy-btn" style="position:absolute;top:10px;right:10px;z-index:2;" onclick="copyDocPre(this)"><i class="fa fa-copy"></i></button>
              <pre class="line-numbers"><code class="language-json">{
    "id": "b522a295-e404...",
    "amount": 100,
    "pixKey": "chave",
    "pixKeyType": "cpf",
    "withdrawStatusId": "PendingProcessing",
    "createdAt": "2025-04-19T20:04:53.166Z",
    "updatedAt": "2025-04-19T20:04:53.166Z"
  }</code></pre>
            </div>
          </div>
        </div>

        <!-- Webhook PIX OUT -->
        <div class="endpoint-card">
          <div class="endpoint-title"><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Webhook PIX OUT</div>
          <p>Retorno automático na rota <code>baasPostbackUrl</code> informada na requisição de saque.</p>

          <h4><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Exemplo de retorno</h4>
          <div style="position:relative;">
            <button class="copy-btn" style="position:absolute;top:10px;right:10px;z-index:2;" onclick="copyDocPre(this)"><i class="fa fa-copy"></i></button>
            <pre class="line-numbers"><code class="language-json">{
    "status": "paid",
    "idTransaction": "TX123",
    "typeTransaction": "PAYMENT"
  }</code></pre>
          </div>
        </div>
      </div>
    </div>

    <div id="copy-toast">Copiado!</div>

    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.js"></script>
    <script>
function copyDocPre(btn) {
  var pre = btn.parentElement.querySelector('pre code');
  var text = pre ? pre.innerText : '';
  if (text) {
    navigator.clipboard.writeText(text);
    showCopyToast('Copiado!');
  }
}
function copyDocText(id) {
  var el = document.getElementById(id);
  if (el) {
    var text = el.innerText || el.textContent;
    navigator.clipboard.writeText(text);
    showCopyToast('Copiado!');
  }
}
function showCopyToast(msg) {
  var toast = document.getElementById('copy-toast');
  toast.innerText = msg;
  toast.style.display = 'block';
  toast.style.opacity = '0.95';
  setTimeout(function(){ toast.style.opacity = '0'; }, 1200);
  setTimeout(function(){ toast.style.display = 'none'; }, 1500);
}
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
@endsection
