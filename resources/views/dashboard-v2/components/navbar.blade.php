@php
    use App\Helpers\Helper;

    $setting = Helper::getSetting();
    $nivel = Helper::meuNivel(auth()->user());

    function formatK($value) {
        if ($value >= 1000000 && fmod($value, 1000000) === 0.0) {
            return 'R$ ' . number_format($value / 1000000, 0, ',', '.') . 'M';
        } elseif ($value >= 1000 && fmod($value, 1000) === 0.0) {
            return 'R$ ' . number_format($value / 1000, 0, ',', '.') . 'k';
        } else {
            return 'R$ ' . number_format($value, 2, ',', '.');
        }
    }

    $totalDepositos = $nivel['total_depositos'];
    $min = $nivel['nivel_atual']?->minimo ?? 0;
    $max = $nivel['nivel_atual']?->maximo ?? 0;

    $porcentagem = 0;

    if ($max > $min) {
        $raw = (($totalDepositos - $min) / ($max - $min)) * 100;
        $porcentagem = min(100, max(0, floor($raw * 100) / 100)); // Trunca para 2 casas sem arredondar
    }
@endphp
<style>
    .logo-website {
        background: url('https://i.imgur.com/VNInZj4.png');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        border: 0;
    }
    [data-bs-theme="dark"] .logo-website {
        background: url('https://i.imgur.com/OwCphDc.png')!important;
        background-size: contain!important;
        background-repeat: no-repeat!important;
        background-position: center!important;
    }
    @media screen and (max-width: 768px) {
        .logo-website {
            width: 110px!important;
            height: 45px!important;
        }
    }
</style>
<nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">

    <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button"
        data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse"
        aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span
                class="toggle-line"></span></span></button>
    <a class="navbar-brand me-1 me-sm-3" href="{{ route('dashboard.v2') }}">
        <div class="d-flex align-items-center">
            <imgs class="me-2 logo-website" alt="" width="80" />
        </div>
    </a>
    
    <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
        <li class="nav-item ps-2 pe-0">
            <div class="dropdown theme-control-dropdown"><a
                    class="nav-link d-flex align-items-center dropdown-toggle fa-icon-wait fs-9 pe-1 py-0" href="#"
                    role="button" id="themeSwitchDropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"><span class="fas fa-sun fs-7" data-fa-transform="shrink-2"
                        data-theme-dropdown-toggle-icon="light"></span><span class="fas fa-moon fs-7"
                        data-fa-transform="shrink-3" data-theme-dropdown-toggle-icon="dark"></span><span
                        class="fas fa-adjust fs-7" data-fa-transform="shrink-2"
                        data-theme-dropdown-toggle-icon="auto"></span></a>
                <div class="dropdown-menu dropdown-menu-end dropdown-caret border py-0 mt-3"
                    aria-labelledby="themeSwitchDropdown">
                    <div class="bg-white dark__bg-1000 rounded-2 py-2">
                        <button class="dropdown-item d-flex align-items-center gap-2" type="button" value="light"
                            data-theme-control="theme"><span class="fas fa-sun"></span>Light<span
                                class="fas fa-check dropdown-check-icon ms-auto text-600"></span></button>
                        <button class="dropdown-item d-flex align-items-center gap-2" type="button" value="dark"
                            data-theme-control="theme"><span class="fas fa-moon" data-fa-transform=""></span>Dark<span
                                class="fas fa-check dropdown-check-icon ms-auto text-600"></span></button>
                        <button class="dropdown-item d-flex align-items-center gap-2" type="button" value="auto"
                            data-theme-control="theme"><span class="fas fa-adjust" data-fa-transform=""></span>Auto<span
                                class="fas fa-check dropdown-check-icon ms-auto text-600"></span></button>
                    </div>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link notification-indicator notification-indicator-primary px-0 fa-icon-wait"
                id="navbarDropdownNotification" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false" data-hide-on-body-scroll="data-hide-on-body-scroll"><span class="fas fa-bell"
                    data-fa-transform="shrink-6" style="font-size: 33px;"></span></a>
            <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end dropdown-menu-card dropdown-menu-notification dropdown-caret-bg"
                aria-labelledby="navbarDropdownNotification">
                <div class="card card-notification shadow-none">
                    <div class="card-header" style="background: {{ $setting->gateway_color }}; color: #fff;">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <h6 class="card-header-title mb-0">Notificações</h6>
                            </div>
                        </div>
                    </div>
                    <div class="scrollbar-overlay" style="max-height:19rem">
                        <div class="list-group list-group-flush fw-normal fs-10">
                            <div class="list-group-item">
                                <a class="notification notification-flush notification-unread" href="#">
                                    <div class="notification-body">
                                        <p class="mb-1"><strong>{{ $setting->gateway_name }} Informa:</strong></p>
                                        <span class="notification-time">Seja bem vindo Sr(a) {{ isset(explode(' ',auth()->user()->name)[0]) ? explode(' ',auth()->user()->name)[0] : auth()->user()->name }} a {{ $setting->gateway_name }}.</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown"><a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-xl">
                    <img class="rounded-circle" src="{{ auth()->user()->avatar }}" alt="" />
                </div>
            </a>
            <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end py-0"
                aria-labelledby="navbarDropdownUser">
                <div class="bg-white dark__bg-1000 rounded-2 py-2">
                    <div class="dropdown-item fw-bold text-warning">{{ auth()->user()->email }}</div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('profile.index.v2') }}">Perfil</a>
                    <a class="dropdown-item" href="#">Configurações</a>
                    <a class="dropdown-item" href="#">Suporte</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item btn-link">Sair</button>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</nav>