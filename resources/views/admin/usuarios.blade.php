@php
$setting = \App\Helpers\Helper::getSetting();

function formatarCpfOuCnpj($valor)
{
    // Remove tudo que não for número
    $numeros = preg_replace('/\D/', '', $valor);

    if (strlen($numeros) === 11) {
        // Formato CPF
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $numeros);
    } elseif (strlen($numeros) > 11) {
        // Formato CNPJ
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/", "$1.$2.$3/$4-$5", $numeros);
    }

    // Retorna apenas os números se não for CPF nem CNPJ esperado
    return $numeros;
}


@endphp
<x-app-layout :route="'[ADMIN] Usuários'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="mb-3 md-mb-0 row">
                <div class="mb-3 md-mb-0 col col-12 col-lg-8 text-start">
                    <h1 class="mb-0 display-5">Usuários</h1>
                </div>

                <div class="col col-12 col-lg-4 text-end">
                    <div class="row">
                        <div class="col col-12">
                            <form method="GET" action="{{ route('admin.usuarios') }}" id="filtroCompleto">
                                <div class="row g-2">
                                    <div class="col col-6">
                                        <input type="search"
                                               class="form-control"
                                               id="buscar"
                                               name="buscar"
                                               placeholder="Buscar"
                                               value="{{ request('buscar') }}"
                                               autofocus>
                                    </div>
                                    <div class="col col-6">
                                        <select class="form-control" id="status" name="status" onchange="document.getElementById('filtroCompleto').submit()">
                                            <option value="ativos" {{ request('status') == "ativos" ? 'selected' : '' }}>Ativos</option>
                                            <option value="banidos" {{ request('status') == "banidos" ? 'selected' : '' }}>Banidos</option>
                                            <option value="pendentes" {{ request('status') == "pendentes" ? 'selected' : '' }}>Pendentes</option>
                                            <option value="todos" {{ request('status') == "todos" ? 'selected' : '' }}>Todos</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start:: row-1 -->
            <div class="row">
                @include('admin.usuarios.partials.card-count', [
                    'label' => 'Cadastros totais',
                    'info' => (clone $users)->count(),
                    'icon' => 'fa-people-group'
                    ])

                @include('admin.usuarios.partials.card-count', [
                    'label' => 'Cadastros Mês',
                    'info' => (clone $users)->whereBetween('data_cadastro', [now()->startOfMonth(), now()->endOfMonth()])->count(),
                    'icon' => 'fa-people-group'
                    ])

                @include('admin.usuarios.partials.card-count', [
                    'label' => 'Cadastros Pendentes',
                    'info' => (clone $users)->where('status', 0)->count(),
                    'icon' => 'fa-clock'
                    ])

                @include('admin.usuarios.partials.card-count', [
                    'label' => 'Usuários banidos',
                    'info' => (clone $users)->where('banido', 1)->count(),
                    'icon' => 'fa-ban'
                    ])
            {{-- End:: row-1 --}}

            <div class="container px-0 mx-0 row">
                <div class="col-xl-12">
                            <div class="row"> <!-- Adicionado para agrupar os cards corretamente -->
                                @foreach ($users as $user)
                                    <div class="mb-3 col-12 col-md-6 col-xl-4">
                                        <div class="relative card card-raised" style="position: relative;">
                                            <div class="p-3 card-body ">
                                                <div class="justify-between px-3 mb-3 bg-transparent row align-center">
                                                    <div class="col-auto text-lg font-bold text-white d-flex align-items-center justify-content-center"
                                                        style="background: url('{{ $user->avatar }}'); background-size: cover; background-position: center; width: 50px; height: 50px; border-radius: 10px;">
                                                    </div>
                                                    <div class="col ps-3" style="line-height: 15px">
                                                        <h4 class="mb-0 font-bold">{{ $user->name }}</h4>
                                                        <p class="mb-0">{{ formatarCpfOuCnpj($user->cpf_cnpj) }}</p>
                                                        <p class="flex items-center gap-1 text-sm">
                                                            @switch($user->permission)
                                                                @case(5)
                                                                    {{-- <div style="width: 8px; height: 8px; background: rgb(255, 123, 0); border-radius:50%; display:inline-block;"></div> --}}
                                                                    <span style="color: gray;">Gerente</span>
                                                                    @break

                                                                @case(3)
                                                                    {{-- <div style="width: 8px; height: 8px; background: rgb(255, 0, 0); border-radius:50%; display:inline-block;"></div> --}}
                                                                    <span style="color: gray;">Administrador</span>
                                                                    @break

                                                                @default
                                                                    {{-- <div style="width: 8px; height: 8px; background: gray; border-radius:50%; display:inline-block;"></div> --}}
                                                                    <span style="color: gray;">Cliente</span>
                                                            @endswitch
                                                        </p>

                                                    </div>
                                                </div>

                                                <p class="card-text">Vendas últimos 7 dias</p>
                                                @php
                                                    $ultimos7Dias = $user->depositos
                                                    ->where('user_id')
                                                        ->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))
                                                        ->sum('deposito_liquido');
                                                @endphp
                                                <h2 class="font-bold card-title">R$ {{ number_format($ultimos7Dias, 2, ',', '.') }}</h2>
                                                <p class="card-text">Saldo em Conta</p>
                                                <h2 class="font-bold card-title">R$ {{ number_format($user->saldo, 2, ',', '.') }}</h2>
                                            </div>
                                            <div class="dropdown" style="position: absolute;top: 20px;right:20px">
                                                <button class="icon-navbar btn btn-sm btn-icon dropdown-toggle "
                                                    id="dropdownMenuUsuario-{{ $user->id }}" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                                        class="text-sm fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="mt-3 dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="dropdownMenuUsuario-{{ $user->id }}">
                                                    <li style="cursor: pointer">
                                                        <a class="text-sm dropdown-item" data-bs-toggle="modal" data-bs-target="#visModal-{{ $user->id }}">
                                                            <i class="fa-solid fa-eye color-gateway"></i>&nbsp;
                                                            <div class="me-3 ">Visualizar</div>
                                                        </a>
                                                    </li>

                                                    <li style="cursor: pointer">
                                                        <a class="text-sm dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal-{{ $user->id }}">
                                                            <i class="fa-solid fa-edit color-gateway"></i>&nbsp;
                                                            <div class="me-3 ">Editar</div>
                                                        </a>
                                                    </li>
                                                    <li style="cursor: pointer">
                                                        <a class="text-sm dropdown-item" data-bs-toggle="modal" data-bs-target="#trocarSenhaModal-{{ $user->id }}">
                                                            <i class="fa-solid fa-user-lock color-gateway"></i>&nbsp;
                                                            <div class="me-3 ">Senha</div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider" />
                                                    </li>
                                                    <li style="cursor: pointer">
                                                        <a class="text-sm dropdown-item" data-bs-toggle="modal" data-bs-target="#aprovarModal-{{ $user->id }}">
                                                            <i class="fa-solid {{ $user->status == 1 ? 'fa-ban text-warning' : 'fa-check color-gateway' }} "></i>&nbsp;
                                                            <div class="me-3">{{ $user->status == 1 ? 'Reprovar' : 'Aprovar' }}</div>
                                                        </a>
                                                    </li>

                                                    <li style="cursor: pointer">
                                                        <a class="text-sm dropdown-item" data-bs-toggle="modal" data-bs-target="#banModal-{{ $user->id }}">
                                                            <i class="fa-solid {{ $user->banido == 0 ? 'fa-user-slash text-danger' : 'fa-user-shield color-gateway' }} "></i>&nbsp;
                                                            <div class="me-3">{{ $user->banido == 0 ? 'Banir' : 'Desbanir' }}</div>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <hr class="dropdown-divider" />
                                                    </li>
                                                    <li style="cursor: pointer">
                                                        <a class="text-sm dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $user->id }}">
                                                            <i class="fa-solid fa-trash color-gateway"></i>&nbsp;
                                                            <div class="me-3">Excluir</div>

                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="p-2 bg-transparent card-footer">
                                                <table class="table mb-0 table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Status</th>
                                                            <th>Documento</th>
                                                            <th>Criado em</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                @if($user->banido == 0)
                                                                    <span class="badge bg-success gateway-badge-success">Ativo</span>
                                                                @else
                                                                    <span class="w-10 text-white badge bg-danger gateway-badge-danger">Bloqueado</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($user->status == 1)
                                                                <span class="badge bg-success gateway-badge-success">Verificado</span>
                                                            @else
                                                                <span class="w-10 text-white badge bg-warning gateway-badge-warning">Análise</span>
                                                            @endif
                                                            </td>
                                                            <td>{{ $user->created_at->format('d/m/Y \à\s H:i') }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                     <!-- Modal Visualizar -->
                                     @include('admin.usuarios.partials.modal-visualizar', ['user' => $user])

                                    <!-- Modal Editar -->
                                    @include('admin.usuarios.partials.modal-edit', ['user' => $user, 'gerentes' => $gerentes, 'gateways' => $gateways])

                                    <!-- Modal Deletar -->
                                    @include('admin.usuarios.partials.modal-delete', ['user' => $user])

                                    <!-- Modal Frente RG -->
                                    @include('admin.usuarios.partials.modal-frente-rg', ['user' => $user])

                                    <!-- Modal Verso RG -->
                                    @include('admin.usuarios.partials.modal-verso-rg', ['user' => $user])

                                    <!-- Modal Selfie RG -->
                                    @include('admin.usuarios.partials.modal-selfie-rg', ['user' => $user])

                                    <!-- Modal Aprovar -->
                                    @include('admin.usuarios.partials.modal-aprovar', ['user' => $user])

                                     <!-- Modal Banir -->
                                     @include('admin.usuarios.partials.modal-banir', ['user' => $user])

                                    <!-- Modal Senha -->
                                    @include('admin.usuarios.partials.modal-trocar-senha', ['user' => $user])


                                @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        $("#table-listar-usuarios").DataTable({
            responsive: true,
            info:false,
            ordering: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            dom: '<"top"f>rt<"bottom"p><"clear">',
                initComplete: function() {
                    // Muda o placeholder do input de busca
                    $('#table-listar-usuarios_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                }
        });
    });
    </script>
    <script>

        function gerarChaveSecret(id){
            let chave = generateUUIDv4();
            document.getElementById(`e-secret-${id}`).value = chave;
        }

        function gerarChaveToken(id){
            let chave = generateUUIDv4();
            document.getElementById(`e-token-${id}`).value = chave;
        }

        function generateUUIDv4() {
            return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
                (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
            );
        }

    </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('buscar');
        input.focus();

        let timeout = null;
        input.addEventListener('input', function () {
            clearTimeout(timeout);
            if (this.value.length >= 3) {
                timeout = setTimeout(() => {
                    document.getElementById('filtroCompleto').submit();
                }, 500);
            }
        });
    });
</script>

</x-app-layout>
