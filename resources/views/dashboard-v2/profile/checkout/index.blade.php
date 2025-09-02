@extends('dashboard-v2.layout')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h6 class="mb-0">Mostrando produtos</h6>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <form class="row gx-2">
                                <div class="col-auto"><small>Sort by:</small></div>
                                <div class="col-auto">
                                    <select class="form-select form-select-sm" aria-label="Bulk actions">
                                        <option selected="">Best Match</option>
                                        <option value="Refund">Newest</option>
                                        <option value="Delete">Price</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-auto pe-0"> <a class="text-600 px-1"
                                href="../../../app/e-commerce/product/product-list.html" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Product List"><span class="fas fa-list-ul"></span></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                @forelse ($checkouts as $checkout)
                <div class="mb-4 col-md-6 col-lg-4">
                    <div class="border rounded-1 h-100 d-flex flex-column justify-content-between pb-3">
                        <div class="overflow-hidden">
                            <div class="position-relative rounded-top overflow-hidden" style="height: 200px;">
                                <a class="d-block h-100" href="{{ route('profile.checkout.produto.v2', ['id' => $checkout->id_unico]) }}#links">
                                    <img class="img-fluid rounded-top h-100 w-100 object-fit-cover" src="{{ $checkout->produto_image }}" alt="{{ $checkout->produto_name }}" />
                                </a>
                                <span class="badge rounded-pill {{ $checkout->status ? 'bg-success' : 'bg-warning text-dark' }} position-absolute mt-2 me-2 z-2 top-0 end-0">
                                    {{ $checkout->status ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                            <div class="p-3">
                                <h5 class="fs-9"><a class="text-1100" href="{{ route('profile.checkout.produto.v2', ['id' => $checkout->id_unico]) }}#links">{{ $checkout->produto_name }}</a></h5>
                                <p class="fs-10 mb-3 text-muted">{{ \Illuminate\Support\Str::limit($checkout->produto_descricao, 60) }}</p>
                                <h5 class="fs-md-7 text-warning mb-0 d-flex align-items-center mb-3">R$ {{ number_format($checkout->produto_valor, 2, ',', '.') }}</h5>
                                <p class="fs-10 mb-1">Pedidos: <strong class="text-success">{{ $checkout->orders->count() }}</strong></p>
                                <p class="fs-10 mb-1">Status: <strong class="{{ $checkout->status ? 'text-success' : 'text-danger' }}">{{ $checkout->status ? 'Ativo' : 'Inativo' }}</strong></p>
                            </div>
                        </div>
                        <div class="d-flex flex-between-center px-3">
                            <div>
                                <!-- Estrelas SVG -->
                                <svg width="16" height="16" fill="#ffc107" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.63.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                                <svg width="16" height="16" fill="#ffc107" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.63.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                                <svg width="16" height="16" fill="#ffc107" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.63.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                                <svg width="16" height="16" fill="#ffc107" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.63.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                                <svg width="16" height="16" fill="#e4e5e9" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.63.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                                <span class="ms-1">({{ $checkout->orders->count() }})</span>
                            </div>
                            <div>
                                <a class="btn btn-sm btn-falcon-default me-2" href="{{ route('profile.checkout.produto.v2', ['id' => $checkout->id_unico]) }}#links" data-bs-toggle="tooltip" data-bs-placement="top" title="Links">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17 7h-6a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10zm-6 8a3 3 0 0 1 0-6h6a3 3 0 0 1 0 6h-6zm-2-7a1 1 0 0 1 0-2h10a1 1 0 1 1 0 2H9zm0 12a1 1 0 0 1 0-2h10a1 1 0 1 1 0 2H9z"/></svg>
                                </a>
                                <a class="btn btn-sm btn-falcon-default me-2" href="{{ route('profile.checkout.produto.v2', ['id' => $checkout->id_unico]) }}#orders" data-bs-toggle="tooltip" data-bs-placement="top" title="Pedidos">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.104 0-2-.896-2-2s.896-2 2-2 2 .896 2 2-.896 2-2 2zm10 0c-1.104 0-2-.896-2-2s.896-2 2-2 2 .896 2 2-.896 2-2 2zM7.334 14h9.332l1.334-8H5.999l1.335 8zM19 4h-3.42l-1.72-2.447A1 1 0 0 0 13 1h-2a1 1 0 0 0-.86.553L8.42 4H5a1 1 0 0 0 0 2h1.18l1.72 10.447A3 3 0 0 0 10.82 19h2.36a3 3 0 0 0 2.92-2.553L17.82 6H19a1 1 0 1 0 0-2z"/></svg>
                                </a>
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-falcon-default" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/></svg>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow rounded-3">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.checkout.produto.v2', ['id' => $checkout->id_unico]) }}">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" class="me-2"><path d="M3 17.25V21h3.75l11.06-11.06-3.75-3.75L3 17.25zm17.71-10.04a1.003 1.003 0 0 0 0-1.42l-2.54-2.54a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg> Editar
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#editModal-{{$checkout->id}}">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" class="me-2"><path d="M3 6h18v2H3V6zm2 3h14v13H5V9zm3 3v7h2v-7H8zm4 0v7h2v-7h-2z"/></svg> Excluir
                                            </a>
                                        </li>
                                    </ul>
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
        <div class="card-footer bg-body-tertiary d-flex justify-content-center">
            <div>
                <button class="btn btn-falcon-default btn-sm me-2" type="button" disabled="disabled"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Prev"><span
                        class="fas fa-chevron-left"></span></button><a
                    class="btn btn-sm btn-falcon-default text-primary me-2" href="#!">1</a><a
                    class="btn btn-sm btn-falcon-default me-2" href="#!">2</a><a class="btn btn-sm btn-falcon-default me-2"
                    href="#!"> <span class="fas fa-ellipsis-h"></span></a><a class="btn btn-sm btn-falcon-default me-2"
                    href="#!">35</a>
                <button class="btn btn-falcon-default btn-sm" type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Next"><span class="fas fa-chevron-right"> </span></button>
            </div>
        </div>
    </div>
@endsection