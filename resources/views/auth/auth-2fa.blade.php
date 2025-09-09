@php
use App\Helpers\Helper;
$setting = Helper::getSetting();
@endphp
<x-guest-layout :route="'Login'">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{
            --color-gateway: linear-gradient(120deg, #f7fafd 0%, #e9f0ff 100%);
        }
        /* Modern Clean Login - Scoped for .login-modern */
        .login-modern {
          min-height: 100vh;
          background: linear-gradient(120deg, #f7fafd 0%, #e9f0ff 100%);
          display: flex;
          align-items: center;
          justify-content: center;
          padding: 0 12px;
        }
        .login-modern .card.card-raised {
          border-radius: 18px !important;
          border: 1.5px solid #eef1f6;
          box-shadow: 0 8px 44px rgba(80, 140, 255, 0.13), 0 1.5px 8px rgba(60,70,120,0.04);
          background: #fff;
          max-width: 440px;
          margin: auto;
          overflow: visible;
        }
        .login-modern .card-body {
          padding: 2.5rem 2.2rem !important;
        }
        .login-modern .form-title {
          color: #28334d !important;
          font-weight: 700;
          font-size: 2.1rem;
          letter-spacing: -0.01em;
        }
        .login-modern .form-subtitle {
          color: #8592b2 !important;
          font-size: 1.05rem;
        }
        .login-modern .form-outlined {
          position: relative;
          margin-top: 2.1rem;
        }
        .login-modern .form-outlined input {
          padding: 1.35rem 2.8rem 0.55rem 1rem;
          border: 1.3px solid #e0e6ef;
          border-radius: 8px;
          outline: none;
          width: 100%;
          background: #f9fafb;
          min-height: 54px !important;
          font-size: 1.04rem;
          color: #27304b;
          font-weight: 500;
          box-shadow: none;
          transition: border .14s, background .14s;
        }
        .login-modern .form-outlined input:focus {
          border-color: #7ea7ff !important;
          background: #fff;
          box-shadow: 0 0 0 2px #b6d1ff4d !important;
        }
        .login-modern .form-outlined label {
          position: absolute;
          top: 1.15rem;
          left: 1rem;
          background: white;
          padding: 0 0.25rem;
          color: #8493ad;
          font-size: 1.03rem;
          font-weight: 500;
          transition: 0.18s cubic-bezier(.4,1.2,.7,1);
          pointer-events: none;
          border-radius: 4px;
        }
        .login-modern .form-outlined input:focus + label,
        .login-modern .form-outlined input:not(:placeholder-shown) + label {
          top: -0.65rem;
          left: 0.9rem;
          font-size: 0.82rem;
          color: #4560e4;
          background: #fff;
          box-shadow: 0 1px 0 0 #fff;
        }
        .login-modern .toggle-visibility {
          position: absolute;
          top: 50%;
          right: 1.1rem;
          transform: translateY(-50%);
          background: transparent;
          border: none;
          font-size: 1.35rem;
          color: #b3bad6;
          cursor: pointer;
          z-index: 3;
          padding: 0;
        }
        .login-modern .toggle-visibility:active {
          color: #4560e4;
        }
        .login-modern .form-check {
          margin-top: 14px !important;
          margin-bottom: 10px !important;
          display: flex;
          align-items: center;
          gap: 0.55em;
        }
        .login-modern .form-check-input {
          border-radius: 5px !important;
          border: 1.2px solid #ced4da !important;
          background: #f5f7fa !important;
          width: 18px;
          height: 18px;
          margin-top: 0;
          margin-right: 0.5em;
          box-shadow: none !important;
          transition: border .13s;
        }
        .login-modern .form-check-input:checked {
          background-color: #4560e4 !important;
          border-color: #4560e4 !important;
          box-shadow: 0 0 2px #4560e4 !important;
        }
        .login-modern .form-check label {
          color: #8592b2 !important;
          font-size: 1.01rem;
          font-weight: 500;
        }
        .login-modern .alert {
          border-radius: 7px;
          font-size: 1rem;
        }
        .login-modern .text-danger {
          font-size: .98rem;
          margin-top: 0.15rem;
          display: block;
        }
        .login-modern .btn.btn-primary {
          border-radius: 8px;
          background: linear-gradient(90deg, #4560e4 0%, #5eafd8 100%) !important;
          color: #fff !important;
          font-weight: 600;
          font-size: 1.10rem;
          letter-spacing: .01em;
          padding: 0.52em 2.1em;
          border: none;
          box-shadow: 0 2px 12px rgba(44,84,156,.08);
          transition: background .15s, color .15s;
        }
        .login-modern .btn.btn-primary:hover, .login-modern .btn.btn-primary:focus {
          background: linear-gradient(90deg, #3650b3 0%, #5eafd8 100%) !important;
        }
        .login-modern a.fw-500 {
          color: #4560e4 !important;
          font-weight: 600;
          letter-spacing: .01em;
          font-size: 1.01rem;
          transition: color .14s;
        }
        .login-modern a.fw-500:hover {
          color: #27304b !important;
          text-decoration: underline !important;
        }
        .login-modern input:-webkit-autofill { 
          box-shadow: 0 0 0 1000px #f9fafb inset !important;
          -webkit-text-fill-color: #27304b !important;
        }

        /* MOBILE IMPROVEMENTS */
        @media (max-width: 575px) {
          .login-modern {
            padding: 0 20px;
          }
          .login-modern .col-xxl-4,
          .login-modern .col-xl-5,
          .login-modern .col-lg-6,
          .login-modern .col-md-8 {
            width: 100% !important;
            min-width: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
          }
          .login-modern .card.card-raised {
            padding: 0.2rem !important;
            box-shadow: 0 4px 22px rgba(80, 140, 255, 0.18);
            max-width: 100vw;
            border-radius: 0 0 18px 18px;
          }
          .login-modern .card-body {
            padding: 1.2rem 0.6rem !important;
          }
          .login-modern .form-title {
            font-size: 1.45rem;
          }
          .login-modern .form-subtitle {
            font-size: .98rem;
          }
          .login-modern .form-outlined input {
            font-size: 1.01rem;
            padding: 1.15rem 2.2rem 0.5rem 0.7rem;
            min-height: 46px !important;
          }
          .login-modern .form-outlined label {
            font-size: .97rem;
            left: 0.7rem;
          }
          .login-modern .form-outlined input:focus + label,
          .login-modern .form-outlined input:not(:placeholder-shown) + label {
            font-size: 0.75rem;
            left: 0.65rem;
          }
          .login-modern .btn.btn-primary {
            padding: 0.46em 1.35em;
            font-size: 1.01rem;
          }
        }
        @media (min-width: 768px) {
          .login-modern .card.card-raised {
            animation: loginIn 0.6s cubic-bezier(.4,1.5,.7,1);
          }
        }
        @keyframes loginIn {
          from {
            opacity: 0;
            transform: translateY(32px) scale(.97);
          }
          to {
            opacity: 1;
            transform: translateY(0) scale(1);
          }
        }
    </style>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="login-modern w-100">
      <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8">
          <div class="mt-5 mb-4 card card-raised shadow-10 mt-xl-10">
              <div class="p-5 card-body">
                  <div class="text-center">
                      <img class="mb-3" src="{{ asset($setting->gateway_logo) }}" alt="..." style="height: 48px" />
                      <h1 class="mb-0 display-5 form-title">Validação 2FA</h1>
                      <div class="mb-5 subheading-1 form-subtitle">para continuar</div>
                  </div>

                  <form method="POST" action="{{ route('2fa.check') }}">
                      @csrf
                      @if (session('error'))
                      <div class="alert alert-danger w-100">{{  session('error') }} </div>
                      @endif

                      <div class="d-flex justify-content-center gap-2 mb-3">
                          <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&pcampaignid=web_share" target="_blank">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Play Store" style="width: 120px;height: 100%;">
                          </a>
                          <a href="https://apps.apple.com/br/app/google-authenticator/id388497605" target="_blank">
                            <img src="https://www.gov.br/pt-br/imagens-de-servicos/apple.png" alt="Apple Store" style="width: 130px;">
                          </a>
                      </div>

                      @php if($isRegistered == false): @endphp
                      <div class="mb-4">
                          <h5 class='text-center'>Escaneie o código 2FA</h5>
                            <div class='text-center mb-2'>
                                <img src='{{ $qrCodeUrl }}' alt='QR Code' style='margin: 0 auto;'>
                            </div>
                            <div class='text-center mb-3' style='display: flex; justify-content: center; align-items: center; gap: 8px;'>
                                    <span style='font-weight: bold;'>Código secreto: 
                                        <span id='secret-code' style='background: #f5f5f5; color: #333; padding: 4px 8px; border-radius: 5px; font-weight:normal;'>{{ $infoUser->secret_2fa }}</span>
                                    </span>
                                    <img src='https://cdn.icon-icons.com/icons2/931/PNG/512/copy_paste_icon-icons.com_72426.png' width="30" class='copy-icon' alt='Copiar' onclick='copySecretCode()'>
                            </div>
                      </div>
                     @php endif; @endphp
                      <div class="mb-4">
                          <div class="form-outlined">
                              <input type="text" id="2fa_code" name="2fa_code" class="form-control" placeholder=" " autocomplete="current-password" required />
                              <label for="2fa_code">Código</label>
                          </div>
                      </div>

                      <div class="mt-4 mb-0 form-group d-flex align-items-center justify-content-between">
                          <a class="small fw-500 text-decoration-none" href="/login">Sair da conta</a>
                          <button type="submit" class="btn btn-primary" >Acessar</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
    </div>
    <script>
        function copySecretCode() {
            const secretCode = document.getElementById('secret-code').innerText;
            navigator.clipboard.writeText(secretCode).then(() => {
                showToast('success', 'Código secreto copiado!');
            }).catch(err => {
                showToast('error', 'Erro ao copiar o código');
            });
        }
    </script>
</x-guest-layout>