@props([
    'user'
])
<div class="modal fade" id="banModal-{{ $user->id }}" tabindex="-1" aria-labelledby="banModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="banModalLabel-{{ $user->id }}">{{ $user->banido == 0 ? 'Banir' : 'Desbanir' }} usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você tem certeza que deseja {{ $user->banido == 0 ? 'banir' : 'desbanir' }} o usuário <span class="{{ $user->banido == 0 ? 'text-warning' : 'text-success' }}">{{ $user->name }}</span>?</p>
            </div>
            <div class="gap-3 modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('admin.usuarios.mudarstatus') }}">
                    @csrf
                    <input id="id" name="id" value="{{$user->id}}" hidden />
                    <input id="tipo" name="tipo" value="banido" hidden />
                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
