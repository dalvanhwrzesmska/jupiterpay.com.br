<style>
.chavesapi-modern-container {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f6f8fa;
  padding: 2rem 0;
}
.chavesapi-modern-row {
  display: flex;
  gap: 2.5rem;
  width: 100%;
  max-width: 1300px;
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
</style>
<div id="chavesapi-toast" style="display:none;position:fixed;z-index:9999;left:50%;bottom:32px;transform:translateX(-50%);background:#222;color:#fff;padding:0.7rem 1.5rem;border-radius:2rem;font-size:1.05rem;box-shadow:0 2px 12px 0 rgba(60,80,180,0.13);transition:opacity 0.2s;opacity:0.95;">
  Copiado!
</div>
<x-app-layout :route="'Token API PIX'">
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
          <span id="token" class="chavesapi-modern-key-box">***********************</span>
          <button class="chavesapi-modern-btn" id="btn-show-key-token" title="Mostrar/ocultar token" onclick="mostrarToken()"><i class="fa-solid fa-eye"></i></button>
          <button class="chavesapi-modern-btn" title="Copiar token" onclick="copiarToken()"><i class="fa-solid fa-copy"></i></button>
        </div>
        <div class="chavesapi-modern-key-label">Secret</div>
        <div class="chavesapi-modern-key-row">
          <span id="secret" class="chavesapi-modern-key-box">***********************</span>
          <button class="chavesapi-modern-btn" id="btn-show-key-secret" title="Mostrar/ocultar secret" onclick="mostrarSecret()"><i class="fa-solid fa-eye"></i></button>
          <button class="chavesapi-modern-btn" title="Copiar secret" onclick="copiarSecret()"><i class="fa-solid fa-copy"></i></button>
        </div>
        <input id="chave-secret" value="{{ $secret }}" style="display: none;" />
        <input id="chave-token" value="{{ $token }}" style="display: none;" />
        <div class="chavesapi-modern-endpoint-label">API Endpoint</div>
        <div class="chavesapi-modern-endpoint-row">
          <input type="text" id="endpoint" name="endpoint" value="{{ env('APP_URL').'/api/' }}" class="chavesapi-modern-endpoint-box" readonly>
          <button class="chavesapi-modern-btn" type="button" onclick="copyToClipboard()" title="Copiar endpoint"><i class="fa-solid fa-copy"></i></button>
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
      if (token.innerText === "***********************") {
        token.innerText = '{{ $token }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
      } else {
        token.innerText = '***********************';
        btnCode.innerHTML = `<i class="fa-solid fa-eye"></i>`;
      }
    }
    function mostrarSecret() {
      var token = document.getElementById("secret");
      var btnCode = document.getElementById('btn-show-key-secret');
      if (token.innerText === "***********************") {
        token.innerText = '{{ $secret }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
      } else {
        token.innerText = '***********************';
        btnCode.innerHTML = `<i class="fa-solid fa-eye"></i>`;
      }
    }
  </script>
</x-app-layout>
