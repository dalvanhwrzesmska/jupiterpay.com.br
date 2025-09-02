@props([
    'user'
])
<div class="modal fade" id="aprovarModal-{{ $user->id }}" tabindex="-1" aria-labelledby="aprovarModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aprovarModalLabel-{{ $user->id }}">{{ $user->status == 1 ? 'Reprovar' : 'Aprovar' }} usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você tem certeza que deseja {{ $user->status == 1 ? 'reprovar' : 'aprovar' }} o usuário <span class="{{ $user->status == 1 ? 'text-warning' : 'text-success' }}">{{ $user->name }}</span>?</p>
            </div>
            <div class="gap-3 modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('admin.usuarios.mudarstatus') }}">
                    @csrf
                    <input id="id" name="id" value="{{$user->id}}" hidden />
                    <input id="tipo" name="tipo" value="status" hidden />
                    <button type="submit" class="btn btn-success">Aprovar</button>
                </form>
            </div>
        </div>
    </div>
</div>
