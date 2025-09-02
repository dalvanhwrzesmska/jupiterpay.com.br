

@php
    $setting = \App\Helpers\Helper::getSetting();
    $porcentagem = $setting->gerente_percentage;
@endphp
<x-app-layout :route="'[GERENCIA] Clientes'">
    <style>
        .table-responsive {
    overflow: visible !important;
}
    </style>

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Dashboard</h1>
                </div>
            </div>
            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-4 col-12">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-6"><button id="shareButton" class="justify-center w-4 btn btn-primary align-center"><i class="text-sm fa-solid fa-share-from-square"></i></button> {{env('APP_URL')."/register?ref=".auth()->user()->code_ref}}</div>
                                    <div class="card-text">Seu link de compartilhamento</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid fa-share"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ (clone $users)->where('indicador_ref', auth()->user()->code_ref)->count() }}</div>
                                    <div class="card-text">Indicações</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid fa-people-group"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ (clone $users)->count() }}</div>
                                    <div class="card-text">Clientes</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid fa-people-group"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">

                                    <div class="display-5">R$ {{ number_format(((float) $comissoes * $porcentagem / 100), '2',',','.') }}</div>
                                    <div class="card-text">Minha comissão</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid fa-people-group"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ $usersTotal }}</div>
                                    <div class="card-text">Clientes (total)</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid fa-people-group"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End:: row-1 --}}

            <div class="mb-3 row" style="min-height:70vh;">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-white card-header">
                            <h6>Meus clientes</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-listar-usuarios" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">Status</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Contato</th>
                                            <th scope="col">Faturamento</th>
                                            <th scope="col">Comissões</th>
                                            <th scope="col">Data de Cadastro</th>
                                             @if (auth()->user()->gerente_aprovar)
                                                <th scope="col"></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                @if($user->status == 0)
                                                    <span class="text-white badge text-bg-danger">Pendente de doc</span>
                                                @elseif($user->status == 1)
                                                    <span class="text-white badge text-bg-success">Aprovado</span>
                                                @elseif($user->status == 5)
                                                    <span class="text-white badge text-bg-warning">Aguardando aprovação</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                                    <a href="tel:{{$user->telefone}}" target="_blank">
                                                        <button type="button" class="btn btn-outline-info"><i class="fa-solid fa-phone"></i></button>
                                                    </a>
                                                    @php
                                                        $hora = now()->hour; // Usa a hora atual do servidor (Carbon instance)
                                                        if ($hora >= 5 && $hora < 12) {
                                                            $saudacao = 'Bom dia';
                                                        } elseif ($hora >= 12 && $hora < 18) {
                                                            $saudacao = 'Boa tarde';
                                                        } else {
                                                            $saudacao = 'Boa noite';
                                                        }

                                                        $gerente_name = explode(' ',auth()->user()->name)[0];
                                                        $mensagem = $saudacao . ' ' . $user->name.'. Meu nome é '.$gerente_name.', sou seu gerente na '.$setting->gateway_name.'!';
                                                        $mensagemEncode = urlencode($mensagem);
                                                    @endphp

                                                    <a href="https://api.whatsapp.com/send?phone=55{{ $user->telefone }}&text={{ $mensagemEncode }}" target="_blank">
                                                        <button type="button" class="btn btn-outline-success"><i class="fa-brands fa-whatsapp"></i></button>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>R$ {{ number_format($user->depositos->where('status','PAID_OUT')->sum('amount'), 2, ',', '.') }}</td>

                                            <td>
                                                R$ {{ number_format($user->comissoes->sum('comission_value'), '2', ',','.') }}
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                                            @if (auth()->user()->gerente_aprovar)
                                                <td>
                                                <div class="position-relative d-inline-block">
                                                 <div class="dropdown">
                                                    <button class="icon-navbar btn btn-sm btn-icon dropdown-toggle"
                                                        id="dropdownMenuUsuario-{{ $user->id }}" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class="text-sm fa-solid fa-ellipsis-vertical"></i>
                                                    </button>
                                                        <ul class="mt-3 dropdown-menu dropdown-menu-end"
                                                            aria-labelledby="dropdownMenuUsuario-{{ $user->id }}">
                                                             <li style="cursor: pointer">
                                                                    @if ($user->status == 1)
                                                                        <a class="text-sm dropdown-item"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#statusModal-{{ $user->id }}"
                                                                        data-id="{{ $user->id }}"
                                                                        data-name="{{ $user->name }}"
                                                                        data-email="{{ $user->email }}"
                                                                        data-saldo="{{ $user->saldo }}"
                                                                        data-permission="{{ $user->permission }}">
                                                                            <i class="fa-solid fa-user text-warning"></i>&nbsp;
                                                                            <div class="me-3 ">Em Análise</div>
                                                                        </a>
                                                                    @else
                                                                     <a class="text-sm dropdown-item"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#statusModal-{{ $user->id }}"
                                                                        data-id="{{ $user->id }}"
                                                                        data-name="{{ $user->name }}"
                                                                        data-email="{{ $user->email }}"
                                                                        data-saldo="{{ $user->saldo }}"
                                                                        data-permission="{{ $user->permission }}">
                                                                            <i class="fa-solid fa-user-plus text-success"></i>&nbsp;
                                                                            <div class="me-3 ">Aprovar</div>
                                                                        </a>
                                                                    @endif
                                                                </li>
                                                        </ul>
                                                </div>
                                                </div>
                                            </td>

                                            <!-- Modal confirmar envio de senha -->
                                        {{-- <div class="modal fade" id="resetarSenhaModal-{{ $user->id }}" tabindex="-1" aria-labelledby="resetarSenhaModalLabel-{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="resetarSenhaModalLabel-{{ $user->id }}">Resetar senha</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Deseja enviar uma nova senha para o usuário <span class="text-success">{{ $user->name }}</span>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                                                        <form method="POST" action="">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning">Resetar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="modal fade" id="statusModal-{{ $user->id }}" tabindex="-1" aria-labelledby="statusModalLabel-{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="statusLabel-{{ $user->id }}">{{ $user->status == 1 ? 'Marcar como Em analise' : 'Aprovar cliente' }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Deseja {{ $user->status == 1 ? 'colocar em analise' : 'aprovar cliente' }} o cliente <span class="text-success">{{ $user->name }}</span>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                                                        <form method="POST" action="{{ route('admin.usuarios.mudarstatus') }}">
                                                                    <input id="id" name="id" value="{{$user->id}}" hidden />
                                                                    <input id="tipo" name="tipo" value="status" hidden />
                                                                    @csrf
                                                                    @if ($user->status == 1)
                                                                    <button type="submit" name="reavaliar" value="true" class="btn btn-warning">
                                                                         <i class="fa-solid fa-user text-warning"></i>&nbsp;
                                                                        Colocar em Análise
                                                                        </button>
                                                                    @else
                                                                    <button type="submit" name="aprovar" value="true" class="btn btn-success">
                                                                         <i class="fa-solid fa-user text-success"></i>&nbsp;
                                                                        Aprovar Usuário
                                                                        </button>
                                                                    @endif
                                                                </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                            @endif

                                        </tr>


                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
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
    document.getElementById('shareButton').addEventListener('click', async () => {
        if (navigator.share) {
            try {
                await navigator.share({
                    title: "{{ env('APP_NAME') }}",
                    text: 'Somos o maior ecossistema de soluções integradas para negócios digitais no Brasil, com ferramentas completas que simplificam e escalam operações de qualquer tamanho.',
                    url: "{{env('APP_URL')."/register?ref=".auth()->user()->code_ref}}"
                });
                console.log('Compartilhado com sucesso!');
            } catch (err) {
                console.error('Erro ao compartilhar:', err);
            }
        } else {
            console.log('O compartilhamento não é suportado neste navegador.');
        }
    });
</script>
</x-app-layout>
