<x-app-layout :route="'Check-out'">
    <div class="main-content app-content bg-light min-vh-100 py-4">
        <div class="container-fluid">

            <div class="row mb-4 align-items-center">
                <div class="col-12 col-lg-8">
                    <h2 class="display-5 fw-bold mb-0">
                        <i class="fa-solid fa-store me-2 text-primary"></i>Produtos da Loja
                    </h2>
                    <p class="text-muted">Gerencie e visualize seus produtos de forma moderna e atrativa.</p>
                </div>
                <div class="col-12 col-lg-4 d-flex justify-content-end">
                    <form method="GET" action="{{ route('profile.checkout') }}" class="flex-grow-1 me-2" id="filtroCompleto">
                        <div class="input-group shadow-sm rounded">
                            <input type="search"
                                class="form-control border-0 ps-4"
                                id="buscar"
                                name="buscar"
                                placeholder="Buscar produtos..."
                                value="{{ request('buscar') }}"
                                autofocus
                                style="box-shadow: none;">
                            <span class="input-group-text bg-white border-0">
                                <i class="fa fa-search text-primary"></i>
                            </span>
                        </div>
                    </form>
                    <button type="button" class="btn btn-gradient-primary d-flex align-items-center ms-2 shadow"
                        data-bs-toggle="modal" data-bs-target="#addproduto">
                        <i class="fa-solid fa-plus me-2"></i> Novo Produto
                    </button>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($checkouts as $checkout)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card border-0 shadow-lg rounded-4 h-100 p-2 produto-card-hover position-relative">
                            <div class="produto-card-img-container position-relative rounded-4 overflow-hidden mb-3" style="height: 220px;">
                                <img src="{{$checkout->produto_image}}" class="w-100 h-100 object-fit-cover produto-card-img" alt="{{ $checkout->produto_name }}">
                                @if ($checkout->status)
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-success text-white px-3 py-2 rounded-pill shadow">Ativo</span>
                                @else
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark px-3 py-2 rounded-pill shadow">Inativo</span>
                                @endif
                            </div>
                            <div class="card-body p-2 d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <h5 class="mb-0 flex-grow-1 text-truncate fw-semibold">{{ $checkout->produto_name }}</h5>
                                    <span class="text-primary fw-bold fs-5 ms-2">R$ {{ number_format($checkout->produto_valor, 2, ',', '.') }}</span>
                                </div>
                                <div class="mb-2 text-muted small" style="min-height: 42px;">{{ \Illuminate\Support\Str::limit($checkout->produto_descricao, 60) }}</div>
                                <div class="d-flex justify-content-between align-items-center mt-auto" style="gap: 20px;">
                                    <a href="/produtos/visualizar/{{ $checkout->id_unico }}#links" class="btn btn-light btn-sm border-0 shadow-sm text-primary me-1">
                                        <i class="fa-solid fa-link"></i> Links
                                    </a>
                                    <a href="/produtos/visualizar/{{ $checkout->id_unico }}#orders" class="btn btn-light btn-sm border-0 shadow-sm text-success me-1">
                                        <i class="fa-solid fa-cart-arrow-down"></i> Pedidos
                                        <span class="badge bg-secondary ms-1">{{ $checkout->orders->count() }}</span>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm border-0 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical text-secondary"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow rounded-3">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.checkout.produto', ['id' => $checkout->id_unico]) }}">
                                                    <i class="fa-solid fa-pencil me-2 text-primary"></i> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="#!" data-bs-toggle="modal" data-bs-target="#editModal-{{$checkout->id}}">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i> Excluir
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Excluir Produto -->
                        <div class="modal fade" id="editModal-{{$checkout->id}}" tabindex="-1" aria-labelledby="editModal-{{$checkout->id}}Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4">
                                    <div class="modal-header bg-danger text-white rounded-top-4">
                                        <h6 class="modal-title" id="editModal-{{$checkout->id}}Label"><i class="fa-solid fa-trash me-2"></i>Excluir produto</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p>Você tem certeza que deseja excluir o produto:</p>
                                        <h6 class="fw-bold text-danger">{{ $checkout->produto_name }}?</h6>
                                    </div>
                                    <div class="modal-footer gap-2">
                                        <button type="button" class="btn btn-outline-dark btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                        <form method="POST" action="{{ route('profile.checkout.delete', ['id'=> $checkout->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center rounded-4 shadow-sm py-5">
                            <i class="fa-solid fa-box-open fa-2x mb-2 text-secondary"></i>
                            <div class="fs-5">Nenhum produto cadastrado ainda.</div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Produto -->
    <div class="modal fade" id="addproduto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary text-white rounded-top-4">
                    <h6 class="modal-title"><i class="fa-solid fa-plus me-2"></i>Novo Produto</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('profile.checkout.create') }}" enctype="multipart/form-data" class="rounded-bottom-4">
                    @csrf
                    @method('POST')
                    <div class="modal-body px-4">
                        <div class="row gy-2">
                            <div class="col-xl-12">
                                <label for="produto_name" class="form-label">Nome do Produto</label>
                                <input type="text" class="form-control @error('produto_name') is-invalid @enderror"
                                    name="produto_name" value="{{ old('produto_name') }}" required>
                                @error('produto_name')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-12">
                                <label for="produto_valor" class="form-label">Preço do Produto</label>
                                <input type="text" class="form-control @error('produto_valor') is-invalid @enderror"
                                    name="produto_valor" value="{{ old('produto_valor') }}" required>
                                @error('produto_valor')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-12">
                                <label for="produto_descricao" class="form-label">Descrição</label>
                                <textarea class="form-control @error('produto_descricao') is-invalid @enderror" name="produto_descricao"
                                    rows="2" required>{{ old('produto_descricao') }}</textarea>
                                @error('produto_descricao')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-6">
                                <label for="produto_tipo" class="form-label">Tipo do Produto</label>
                                <select class="form-select @error('produto_tipo') is-invalid @enderror"
                                    name="produto_tipo" required>
                                    <option value="info" selected>Info Produto</option>
                                    <option value="fisico">Produto Físico</option>
                                </select>
                                @error('produto_tipo')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-6">
                                <label for="produto_tipo_cob" class="form-label">Tipo de Cobrança</label>
                                <select class="form-select @error('produto_tipo_cob') is-invalid @enderror"
                                    name="produto_tipo_cob" required>
                                    <option value="unico" selected>Único</option>
                                    <option value="recorrente">Recorrente</option>
                                </select>
                                @error('produto_tipo_cob')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="button" data-bs-dismiss="modal" class="btn btn-outline-dark w-50">Cancelar</button>
                                    <button type="submit" class="btn btn-gradient-primary w-50">Cadastrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom styles for modern look -->
    <style>
        .main-content {
            /*background: linear-gradient(135deg, #f8fafc 0%, #e8f0fe 100%);*/
        }
        .produto-card-hover {
            transition: box-shadow .2s, transform .2s;
        }
        .produto-card-hover:hover {
            box-shadow: 0 8px 32px 0 rgba(60,80,180,0.15), 0 1.5px 3px 0 rgba(0,0,0,0.10);
            transform: translateY(-3px) scale(1.03);
        }
        .produto-card-img-container {
            background: #f4f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .produto-card-img {
            object-fit: cover;
            width: 100%;
            height: 100%;
            transition: transform .2s;
        }
        .produto-card-hover:hover .produto-card-img {
            transform: scale(1.07) rotate(-1deg);
        }
        .btn-gradient-primary {
            background: linear-gradient(90deg, #005bea 0%, #3c8ce7 100%);
            border: none;
            color: #fff;
        }
        .btn-gradient-primary:hover, .btn-gradient-primary:focus {
            background: linear-gradient(90deg, #3c8ce7 0%, #005bea 100%);
            color: #fff;
        }
        .modal-header.bg-gradient-primary {
            background: linear-gradient(90deg, #005bea 0%, #3c8ce7 100%) !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3c8ce7;
            box-shadow: 0 0 0 .2rem rgba(60,140,231,0.10);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('buscar');
            if(input){
                input.focus();
                input.select();

                let timeout = null;
                input.addEventListener('input', function () {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        document.getElementById('filtroCompleto').submit();
                    }, 500);
                });
            }
        });
    </script>
</x-app-layout>