@props([
    'user'
])
<div class="modal fade" id="trocarSenhaModal-{{ $user->id }}" tabindex="-1" aria-labelledby="trocarSenhaModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.usuarios.edit', ['id' => $user->id]) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="trocarSenhaModalLabel-{{ $user->id }}">Alterar senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <input type="hidden" id="editUserId" name="id">
                <div class="m-3">
                    <label for="editSenha-{{ $user->id }}" class="form-label">Nova senha</label>
                    <input type="text"  class="form-control" id="editSenha-{{ $user->id }}" name="password">
                </div>
                <div class="gap-3 modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
