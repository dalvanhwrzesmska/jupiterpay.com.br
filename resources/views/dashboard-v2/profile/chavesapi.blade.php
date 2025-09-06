<style>
.chavesapi-modern-container {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 0;
}
.chavesapi-modern-row {
  display: flex;
  gap: 2.5rem;
  width: 100%;
  max-width: 1400px;
  justify-content: center;
}
.chavesapi-modern-info-card {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 5px 24px 0 rgba(60,80,180,0.10), 0 1.5px 3px 0 rgba(0,0,0,0.04);
  padding: 2.2rem 2rem 2rem 2rem;
  flex: 2 1 0;
  min-width: 320px;
  max-width: 850px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.chavesapi-modern-card {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 6px 32px 0 rgba(60,80,180,0.10), 0 1.5px 3px 0 rgba(0,0,0,0.04);
  padding: 2.5rem 2.2rem 2rem 2.2rem;
  flex: 1 1 0;
  min-width: 260px;
  max-width: 420px;
  margin: 0 auto;
}
.chavesapi-modern-title {
  font-size: 2rem;
  font-weight: 700;
  color: #222;
  margin-bottom: 0.7rem;
  text-align: left;
}
.chavesapi-modern-desc {
  color: #5a5a5a;
  font-size: 1.08rem;
  margin-bottom: 0.7rem;
}
.chavesapi-modern-key-label {
  font-size: 1.01rem;
  color: #3c4a5a;
  font-weight: 500;
  margin-bottom: 0.3rem;
}
.chavesapi-modern-key-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1.3rem;
}
.chavesapi-modern-key-box {
  background: #f1f5fd;
  border-radius: 10px;
  padding: 0.7rem 1.1rem;
  font-size: 1.08rem;
  font-family: monospace;
  color: #222;
  min-width: 170px;
  letter-spacing: 0.04em;
  border: 1px solid #e3e8ee;
  transition: background 0.2s;
}
.chavesapi-modern-btn {
  border: none;
  background: #e3e8ee;
  color: #3c8ce7;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  transition: background 0.15s, color 0.15s;
  cursor: pointer;
}
.chavesapi-modern-btn:hover {
  background: #3c8ce7;
  color: #fff;
}
.chavesapi-modern-copied {
  color: #1a7f37;
  font-size: 0.98rem;
  margin-left: 0.3rem;
  font-weight: 500;
  transition: opacity 0.2s;
}
.chavesapi-modern-endpoint-label {
  font-size: 1.01rem;
  color: #3c4a5a;
  font-weight: 500;
  margin-bottom: 0.3rem;
  margin-top: 1.5rem;
}
.chavesapi-modern-endpoint-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.chavesapi-modern-endpoint-box {
  background: #f1f5fd;
  border-radius: 10px;
  padding: 0.7rem 1.1rem;
  font-size: 1.08rem;
  font-family: monospace;
  color: #222;
  border: 1px solid #e3e8ee;
  width: 100%;
}
@media (max-width: 900px) {
  .chavesapi-modern-row {
    flex-direction: column-reverse;
    gap: 1.5rem;
    align-items: stretch;
  }
  .chavesapi-modern-info-card, .chavesapi-modern-card { max-width: 100%; min-width: 0; }
  .chavesapi-modern-container { padding-top: 0.7rem; }
}
@media (max-width: 600px) {
  .chavesapi-modern-card { padding: 1.2rem 0.7rem; }
}
/* Dark mode para chaves de API */
.dark .chavesapi-modern-info-card,
[data-bs-theme="dark"] .chavesapi-modern-info-card,
body.dark .chavesapi-modern-info-card,
.dark .chavesapi-modern-card,
[data-bs-theme="dark"] .chavesapi-modern-card,
body.dark .chavesapi-modern-card {
  background: #23272f !important;
  color: #e2e6ef !important;
  border-color: #232837 !important;
  box-shadow: 0 5px 24px 0 rgba(0,0,0,0.18), 0 1.5px 3px 0 rgba(0,0,0,0.08);
}
.dark .chavesapi-modern-title,
[data-bs-theme="dark"] .chavesapi-modern-title,
body.dark .chavesapi-modern-title {
  color: #fff !important;
}
.dark .chavesapi-modern-desc,
[data-bs-theme="dark"] .chavesapi-modern-desc,
body.dark .chavesapi-modern-desc {
  color: #bfc8e2 !important;
}
.dark .chavesapi-modern-key-label,
[data-bs-theme="dark"] .chavesapi-modern-key-label,
body.dark .chavesapi-modern-key-label,
.dark .chavesapi-modern-endpoint-label,
[data-bs-theme="dark"] .chavesapi-modern-endpoint-label,
body.dark .chavesapi-modern-endpoint-label {
  color: #bfc8e2 !important;
}
.dark .chavesapi-modern-key-box,
[data-bs-theme="dark"] .chavesapi-modern-key-box,
body.dark .chavesapi-modern-key-box,
.dark .chavesapi-modern-endpoint-box,
[data-bs-theme="dark"] .chavesapi-modern-endpoint-box,
body.dark .chavesapi-modern-endpoint-box {
  background: #181c24 !important;
  color: #e2e6ef !important;
  border-color: #232837 !important;
}
.dark .chavesapi-modern-btn,
[data-bs-theme="dark"] .chavesapi-modern-btn,
body.dark .chavesapi-modern-btn {
  background: #232837 !important;
  color: #3c8ce7 !important;
  border: none;
}
.dark .chavesapi-modern-btn:hover,
[data-bs-theme="dark"] .chavesapi-modern-btn:hover,
body.dark .chavesapi-modern-btn:hover {
  background: #3c8ce7 !important;
  color: #fff !important;
}
.dark .chavesapi-modern-copied,
[data-bs-theme="dark"] .chavesapi-modern-copied,
body.dark .chavesapi-modern-copied {
  color: #6ee7b7 !important;
}
.dark #chavesapi-toast,
[data-bs-theme="dark"] #chavesapi-toast,
body.dark #chavesapi-toast {
  background: #232837 !important;
  color: #fff !important;
}
</style>
<div id="chavesapi-toast" style="display:none;position:fixed;z-index:9999;left:50%;bottom:32px;transform:translateX(-50%);background:#222;color:#fff;padding:0.7rem 1.5rem;border-radius:2rem;font-size:1.05rem;box-shadow:0 2px 12px 0 rgba(60,80,180,0.13);transition:opacity 0.2s;opacity:0.95;">
  Copiado!
</div>
@extends('dashboard-v2.layout')
@section('title', 'Chaves de API PIX')
@section('description', 'Gerencie suas chaves de API para integração com o sistema PIX.')
@section('content')
  <div class="chavesapi-modern-container">
    <div class="chavesapi-modern-row">
      <div class="chavesapi-modern-info-card">
        <div class="chavesapi-modern-title">Sobre a API PIX</div>
        <div class="chavesapi-modern-desc">
          Nossa API foi desenvolvida com tecnologia de ponta para oferecer desempenho, segurança e escalabilidade. Com uma arquitetura moderna e eficiente, ela realiza o processamento de transações de forma ágil e segura, garantindo a melhor experiência tanto para o lojista quanto para o cliente final.
        </div>
        <div class="chavesapi-modern-desc">
          Oferecemos um painel de controle completo e personalizável, que permite a análise detalhada das vendas, além de recursos avançados para o gerenciamento financeiro do seu negócio.
        </div>
        <div class="chavesapi-modern-desc">
          A segurança é um dos nossos pilares. Contamos com sistemas robustos de prevenção a fraudes e proteção de dados, seguindo os mais altos padrões do mercado para garantir a integridade das informações e a tranquilidade dos usuários.
        </div>
        <div class="chavesapi-modern-desc">
          Nossa API se integra facilmente às principais plataformas de e-commerce, proporcionando uma experiência fluida e sem fricções. Além disso, contamos com uma conexão direta com as adquirentes, otimizando o fluxo de pagamento e reduzindo etapas intermediárias.
        </div>
        <div class="chavesapi-modern-desc">
          Seja para escalar seu negócio ou modernizar sua infraestrutura de pagamentos, nossa API é a solução ideal para quem busca inovação, segurança e controle total.
        </div>
      </div>
      <div class="chavesapi-modern-card">
        <div class="chavesapi-modern-title" style="text-align:center;">Chaves de API</div>
        <div class="chavesapi-modern-desc" style="text-align:center;">Utilize as chaves abaixo para integrar seu sistema ao PIX. Mantenha-as em segredo e nunca compartilhe publicamente.</div>
        <div class="chavesapi-modern-key-label">Token</div>
        <div class="chavesapi-modern-key-row">
          <span id="token" class="chavesapi-modern-key-box">********************</span>
          <button class="chavesapi-modern-btn" id="btn-show-key-token" title="Mostrar/ocultar token" onclick="mostrarToken()">
            <span id="icon-eye-token"> 
              <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3C5 3 1.73 7.11 1.13 7.98a1 1 0 0 0 0 1.04C1.73 12.89 5 17 10 17s8.27-4.11 8.87-4.98a1 1 0 0 0 0-1.04C18.27 7.11 15 3 10 3zm0 12c-3.87 0-6.82-3.13-7.72-4C3.18 8.13 6.13 5 10 5s6.82 3.13 7.72 4c-.9.87-3.85 4-7.72 4zm0-7a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
            </span>
          </button>
          <button class="chavesapi-modern-btn" title="Copiar token" onclick="copiarToken()">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
          </button>
        </div>
        <div class="chavesapi-modern-key-label">Secret</div>
        <div class="chavesapi-modern-key-row">
          <span id="secret" class="chavesapi-modern-key-box">********************</span>
          <button class="chavesapi-modern-btn" id="btn-show-key-secret" title="Mostrar/ocultar secret" onclick="mostrarSecret()">
            <span id="icon-eye-secret">
              <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3C5 3 1.73 7.11 1.13 7.98a1 1 0 0 0 0 1.04C1.73 12.89 5 17 10 17s8.27-4.11 8.87-4.98a1 1 0 0 0 0-1.04C18.27 7.11 15 3 10 3zm0 12c-3.87 0-6.82-3.13-7.72-4C3.18 8.13 6.13 5 10 5s6.82 3.13 7.72 4c-.9.87-3.85 4-7.72 4zm0-7a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
            </span>
          </button>
          <button class="chavesapi-modern-btn" title="Copiar secret" onclick="copiarSecret()">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
          </button>
        </div>
        <input id="chave-secret" value="{{ $secret }}" style="display: none;" />
        <input id="chave-token" value="{{ $token }}" style="display: none;" />
        <div class="chavesapi-modern-endpoint-label">API Endpoint</div>
        <div class="chavesapi-modern-endpoint-row">
          <input type="text" id="endpoint" name="endpoint" value="{{ env('APP_URL').'/api/' }}" class="chavesapi-modern-endpoint-box" readonly>
          <button class="chavesapi-modern-btn" type="button" onclick="copyToClipboard()" title="Copiar endpoint">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
          </button>
        </div>
      </div>
    </div>
  </div>
  <script>
    function showChavesToast(msg) {
      var toast = document.getElementById('chavesapi-toast');
      toast.innerText = msg;
      toast.style.display = 'block';
      toast.style.opacity = '0.95';
      setTimeout(function(){ toast.style.opacity = '0'; }, 1200);
      setTimeout(function(){ toast.style.display = 'none'; }, 1500);
    }
    function copyToClipboard() {
      var copyText = document.getElementById("endpoint");
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      document.execCommand("copy");
      showChavesToast('Endpoint copiado!');
    }
    function copiarSecret() {
      var input = document.getElementById("chave-secret");
      navigator.clipboard.writeText(input.value)
        .then(() => {
          showChavesToast("Secret copiado!");
        });
    }
    function copiarToken() {
      var input = document.getElementById("chave-token");
      navigator.clipboard.writeText(input.value)
        .then(() => {
          showChavesToast("Token copiado!");
        });
    }
    function mostrarToken() {
      var token = document.getElementById("token");
      var btnCode = document.getElementById('btn-show-key-token');
      if (token.innerText === "********************") {
        token.innerText = '{{ $token }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
      } else {
        token.innerText = '********************';
        btnCode.innerHTML = `<i class="fa-solid fa-eye"></i>`;
      }
    }
    function mostrarSecret() {
      var token = document.getElementById("secret");
      var btnCode = document.getElementById('btn-show-key-secret');
      if (token.innerText === "********************") {
        token.innerText = '{{ $secret }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
      } else {
        token.innerText = '********************';
        btnCode.innerHTML = `<i class="fa-solid fa-eye"></i>`;
      }
    }
  </script>
@endsection