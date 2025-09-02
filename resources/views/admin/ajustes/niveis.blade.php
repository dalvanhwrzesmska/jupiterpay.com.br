<x-app-layout :route="'[ADMIN] Ajustes de níveis'">
    <style>
        /* Aumenta a escala do switch */
        .form-check-input.custom-switch-lg {
            width: 3rem;
            height: 1.5rem;
        }

        .form-check-input.custom-switch-lg:checked {
            background-color: var(--color-gateway);
        }

        .form-check-input.custom-switch-lg::before {
            transform: scale(1.8);
        }
    </style>

    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Ajuste de Níveis</h1>
                </div>
            </div>

            <!-- Start::row-2 -->
            <div class="row">
                <div class="mb-3 col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <div class="form-check form-switch">
                                <input class="form-check-input custom-switch-lg" name="niveis_ativo" {{ ($niveis_ativo ?? false) ? 'checked' : '' }} value="{{$niveis_ativo}}" type="checkbox" role="switch" id="switchCheckDefault">
                                <label class="ml-3 text-xl form-check-label" for="switchCheckDefault">Sistema de níveis ativo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 col-xl-12">
                        <div class="container-clone row">
                            @foreach ($niveis as $nivel)
                                <div class="mb-3 col-md-6 col-xl-4 col-xxl-3 card-nivel" data-id="{{ $nivel->id }}">
                                    <div class="card card-raised">
                                        <div class="card-body">
                                            <form class="form-nivel" data-id="{{ $nivel->id }}">
                                                <input type="hidden" name="id" value="{{ $nivel->id }}">
                                                <div class="row">

                                                    <div class="mb-2 col-12">
                                                        <label class="form-label">Nome</label>
                                                        <input type="text" class="form-control" name="nome" value="{{ $nivel->nome }}" required>
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <label class="form-label">Mínimo (R$)</label>
                                                        <input type="text" class="form-control" name="minimo" value="{{ $nivel->minimo }}" required>
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <label class="form-label">Máximo (R$)</label>
                                                        <input type="text" class="form-control" name="maximo" value="{{ $nivel->maximo }}" required>
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <label class="form-label">Cor</label>
                                                        <input type="color" class="form-control" name="cor" value="{{ $nivel->cor }}" required>
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <div class="btn-group w-100">
                                                            <button type="button" class="btn btn-danger btn-excluir"><i class="fa fa-trash"></i>&nbsp;Excluir</button>
                                                            <button type="button" class="btn btn-success btn-salvar"><i class="fa fa-save"></i>&nbsp;Salvar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Card modelo para clonagem --}}
                            <div id="card-clone" class="mb-3 col-md-6 col-xl-4 col-xxl-3 d-none">
                                <div class="card card-raised">
                                    <div class="card-body">
                                        <form class="form-nivel" data-id="">
                                            <div class="row">

                                                <div class="mb-2 col-12">
                                                    <label class="form-label">Nome</label>
                                                    <input type="text" class="form-control" name="nome" required>
                                                </div>
                                                <div class="mb-2 col-12">
                                                    <label class="form-label">Mínimo (R$)</label>
                                                    <input type="text" class="form-control" name="minimo" required>
                                                </div>
                                                <div class="mb-2 col-12">
                                                    <label class="form-label">Máximo (R$)</label>
                                                    <input type="text" class="form-control" name="maximo" required>
                                                </div>
                                                <div class="mb-2 col-12">
                                                    <label class="form-label">Cor</label>
                                                    <input type="color" class="form-control" name="cor" value="{{ $nivel->cor }}" required>
                                                </div>
                                                <div class="mb-2 col-12">
                                                    <div class="btn-group w-100">
                                                        <button type="button" class="btn btn-danger btn-excluir"><i class="fa fa-trash"></i>&nbsp;Excluir</button>
                                                        <button type="button" class="btn btn-success btn-salvar"><i class="fa fa-save"></i>&nbsp;Salvar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="clone-card" class="mb-3 col-md-6 col-xl-4 col-xxl-3" style="cursor:pointer;">
                                <div class="card card-raised" style="min-height: 376px">
                                    <div class="card-body d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-plus" style="font-size: 36px;color:rgb(206, 206, 206);"></i>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = "/{{env("ADM_ROUTE")}}/ajustes";

        document.addEventListener("DOMContentLoaded", function () {
            const container = document.querySelector('.container-clone');
            const clonador = document.getElementById('clone-card');
            const modelo = document.getElementById('card-clone');

            // Clonagem
            clonador.addEventListener('click', () => {
                const novo = modelo.cloneNode(true);
                novo.classList.remove('d-none');
                novo.removeAttribute('id');
                novo.querySelectorAll('input').forEach(i => i.value = '');
                container.insertBefore(novo, clonador);
            });

            // Delegação de eventos para excluir/salvar
            container.addEventListener('click', function (e) {
                const card = e.target.closest('.card-nivel') || e.target.closest('#card-clone')?.nextElementSibling;
                const form = e.target.closest('.form-nivel');
                if (!form) return;

                const id = form.dataset.id;

                // EXCLUIR
                if (e.target.closest('.btn-excluir')) {
                    if (id) {
                        if (!confirm('Deseja realmente excluir este nível?')) return;
                        fetch(`${baseUrl}/niveis/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }).then(res => {
                            if (res.ok) {
                                card.remove();
                            } else {
                                showToast('error','Erro ao excluir');
                            }
                        });
                    } else {
                        // Novo (não salvo ainda)
                        card.remove();
                    }
                }

                // SALVAR
                if (e.target.closest('.btn-salvar')) {
                    const formData = new FormData(form);
                    const url = id ? `${baseUrl}/niveis/${id}` : `${baseUrl}/niveis`;
                    const method = 'POST'; // Sempre POST com _method para Laravel

                    if (id) {
                        formData.append('_method', 'PUT');
                    }

                    fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message);
                            if (!id) {
                                form.dataset.id = data.id;
                                card.dataset.id = data.id;
                                form.querySelector('input[name="id"]')?.remove();
                                const inputHidden = document.createElement('input');
                                inputHidden.setAttribute('type', 'hidden');
                                inputHidden.setAttribute('name', 'id');
                                inputHidden.value = data.id;
                                form.appendChild(inputHidden);
                            }
                        } else {
                            showToast('error','Erro ao salvar');
                        }
                    });
                }

            });
        });
        </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        let active = document.querySelector('[name="niveis_ativo"]');
        let url = baseUrl + '/active-niveis';

        active.addEventListener('change', function () {
            const formData = new FormData();
            formData.append('niveis_ativo', active.checked ? 1 : 0);

            fetch(url, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            }).then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                } else {
                    showToast('error', 'Erro ao salvar');
                }
            });
        });
    });
    </script>
</x-app-layout>
