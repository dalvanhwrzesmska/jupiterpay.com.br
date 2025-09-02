@php
use App\Helpers\Helper;
$setting = Helper::getSetting();
@endphp
<x-guest-layout :route="'Cadastrar-me'">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{
            --color-gateway: linear-gradient(120deg, #f7fafd 0%, #e9f0ff 100%);
        }
        /* Modern Clean Register - Scoped for .register-modern */
        .register-modern {
          min-height: 100vh;
          background: linear-gradient(120deg, #f7fafd 0%, #e9f0ff 100%);
          display: flex;
          align-items: center;
          justify-content: center;
        }
        .register-modern .card.card-raised {
          border-radius: 18px;
          border: 1.5px solid #eef1f6;
          box-shadow: 0 8px 44px rgba(80, 140, 255, 0.13), 0 1.5px 8px rgba(60,70,120,0.04);
          background: #fff;
          max-width: 650px;
          margin: auto;
          overflow: visible;
        }
        .register-modern .card-body {
          padding: 2.7rem 2.3rem !important;
        }
        .register-modern .form-title {
          color: #28334d !important;
          font-weight: 700;
          font-size: 2.1rem;
          letter-spacing: -0.01em;
        }
        .register-modern .form-subtitle {
          color: #8592b2 !important;
          font-size: 1.07rem;
        }
        .register-modern .form-outlined {
          position: relative;
          margin-top: 2.1rem;
        }
        .register-modern .form-outlined input {
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
        .register-modern .form-outlined input:focus {
          border-color: #7ea7ff !important;
          background: #fff;
          box-shadow: 0 0 0 2px #b6d1ff4d !important;
        }
        .register-modern .form-outlined label {
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
        .register-modern .form-outlined input:focus + label,
        .register-modern .form-outlined input:not(:placeholder-shown) + label {
          top: -0.65rem;
          left: 0.9rem;
          font-size: 0.82rem;
          color: #4560e4;
          background: #fff;
          box-shadow: 0 1px 0 0 #fff;
        }
        .register-modern .toggle-visibility {
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
        .register-modern .toggle-visibility:active {
          color: #4560e4;
        }
        .register-modern .alert {
          border-radius: 7px;
          font-size: 1rem;
        }
        .register-modern .text-danger {
          font-size: .98rem;
          margin-top: 0.15rem;
          display: block;
        }
        .register-modern .btn.btn-primary {
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
        .register-modern .btn.btn-primary:hover, .register-modern .btn.btn-primary:focus {
          background: linear-gradient(90deg, #3650b3 0%, #5eafd8 100%) !important;
        }
        .register-modern a.fw-500 {
          color: #4560e4 !important;
          font-weight: 600;
          letter-spacing: .01em;
          font-size: 1.01rem;
          transition: color .14s;
        }
        .register-modern a.fw-500:hover {
          color: #27304b !important;
          text-decoration: underline !important;
        }
        .register-modern input:-webkit-autofill { 
          box-shadow: 0 0 0 1000px #f9fafb inset !important;
          -webkit-text-fill-color: #27304b !important;
        }
        .register-modern .row {
          margin-left: 0;
          margin-right: 0;
        }
        @media (max-width: 991px) {
          .register-modern .card.card-raised {
            max-width: 96vw;
            padding: 0.8rem !important;
          }
          .register-modern .card-body {
            padding: 1.3rem 0.5rem !important;
          }
        }
        @media (max-width: 575px) {
          .register-modern .card.card-raised {
            padding: 0.4rem !important;
            max-width: 99vw;
          }
          .register-modern .card-body {
            padding: 1.1rem 0.2rem !important;
          }
        }
        @media (min-width: 768px) {
          .register-modern .card.card-raised {
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

    <div class="register-modern w-100">
      <div class="col-xxl-7 col-xl-10">
          <div class="mt-5 mb-5 card card-raised shadow-10 mt-xl-10">
              <div class="p-5 card-body">
                  <div class="text-center">
                      <img class="mb-3" src="{{ asset($setting->gateway_logo)}}" alt="..." style="height: 48px" />
                      <h1 class="mb-0 display-5 form-title">Crie uma nova conta</h1>
                      <div class="mb-5 subheading-1 form-subtitle">para ter acesso a plataforma</div>
                  </div>
                  <form method="POST" action="{{ route('register') }}">
                      @csrf
                      <input type="hidden" name="ref" value="{{ request('ref') }}">
                      <div class="row">
                          <div class="mb-2 col-sm-12">
                              <div class="form-outlined">
                                  <input type="text"  name="name" value="{{ old('name') }}" class="form-control" placeholder=" " required autocomplete="name" />
                                  <label for="name">Nome Completo</label>
                              </div>
                              @if ($errors->has('name'))
                              <span class="text-danger">{{  $errors->first('name') }} </span>
                              @endif
                          </div>
                      </div>
                      <div class="row">
                          <div class="mb-2 col-sm-6">
                              <div class="form-outlined">
                                  <input type="text"  name="telefone" value="{{ old('telefone') }}" class="form-control" placeholder=" " autocomplete="tel" />
                                  <label for="telefone">Telefone</label>
                              </div>
                              @if ($errors->has('telefone'))
                              <span class="text-danger">{{  $errors->first('telefone') }} </span>
                              @endif
                          </div>
                          <div class="mb-2 col-sm-6">
                              <div class="form-outlined">
                                  <input type="text"  name="username" value="{{ old('username') }}" class="form-control" placeholder=" " autocomplete="username" />
                                  <label for="username">Username</label>
                              </div>
                              @if ($errors->has('username'))
                              <span class="text-danger">{{  $errors->first('username') }} </span>
                              @endif
                          </div>
                      </div>
                      <div class="row">
                          <div class="mb-2 col-sm-12">
                              <div class="form-outlined">
                                  <input type="email"  name="email" value="{{ old('email') }}" class="form-control" placeholder=" " autocomplete="email" required />
                                  <label for="email">Email</label>
                              </div>
                              @if ($errors->has('email'))
                              <span class="text-danger">{{  $errors->first('email') }} </span>
                              @endif
                         </div>
                      </div>
                      <div class="row">
                          <div class="mb-2 col-sm-6">
                              <div class="form-outlined">
                                  <input type="password" id="passwordInput" name="password" class="form-control" placeholder=" " autocomplete="new-password" required />
                                  <label for="passwordInput">Senha</label>
                                  <button type="button" id="togglePassword" class="toggle-visibility" tabindex="-1">
                                      <i id="i-p" class="bi bi-eye-slash"></i>
                                  </button>
                              </div>
                              @if ($errors->has('password'))
                              <span class="text-danger">{{  $errors->first('password') }} </span>
                              @endif
                          </div>
                          <div class="mb-2 col-sm-6">
                              <div class="form-outlined">
                                  <input type="password" id="passwordConfirmationInput" name="password_confirmation" class="form-control" placeholder=" " autocomplete="new-password" required />
                                  <label for="passwordConfirmationInput">Confirmar senha</label>
                                  <button type="button" id="togglePasswordConfirmation" class="toggle-visibility" tabindex="-1">
                                      <i id="i-c" class="bi bi-eye-slash"></i>
                                  </button>
                              </div>
                              @if ($errors->has('password_confirmation'))
                              <span class="text-danger">{{  $errors->first('password_confirmation') }} </span>
                              @endif
                          </div>
                      </div>
                      <div class="mt-4 mb-0 form-group d-flex align-items-center justify-content-between">
                          <a class="small fw-500 text-decoration-none" href="/login">Efetuar login</a>
                          <button type="submit" class="btn btn-primary" >Cadastrar</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
          const toggleBtn = document.getElementById("togglePassword");
          const passwordInput = document.getElementById("passwordInput");
          const icon = toggleBtn.querySelector("#i-p");

          toggleBtn.addEventListener("click", function () {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            icon.classList.toggle("bi-eye");
            icon.classList.toggle("bi-eye-slash");
          });

          const toggleBtnConf = document.getElementById("togglePasswordConfirmation");
          const passwordInputConf = document.getElementById("passwordConfirmationInput");
          const iconConf = toggleBtnConf.querySelector("#i-c");

          toggleBtnConf.addEventListener("click", function () {
            const isPassword = passwordInputConf.type === "password";
            passwordInputConf.type = isPassword ? "text" : "password";
            iconConf.classList.toggle("bi-eye");
            iconConf.classList.toggle("bi-eye-slash");
          });
        });
    </script>
</x-guest-layout>