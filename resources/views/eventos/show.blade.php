@extends('layouts.app')

@section('title', $evento->titulo . ' — FalaQ')

@section('content')
<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm p-3">
            <h4 class="fw-bold mb-3">💬 Faça sua Pergunta</h4>
            <form action="{{ route('eventos.perguntas.store', $evento->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="texto" class="form-label text-secondary">Texto da Pergunta</label>

                    <textarea name="texto" id="texto" rows="4" 
                              class="form-control bg-dark text-white border-secondary @error('texto') is-invalid @enderror"
                              placeholder="Digite sua dúvida ou comentário para o palestrante..."></textarea>

                    @error('texto')
                        <div class="invalid-feedback fw-bold">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Enviar Pergunta</button>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold m-0">📋 Perguntas do Evento</h4>
            <div class="d-flex align-items-center gap-3">
                @can('moderate', $evento)
                    <a href="{{ route('moderacoes.index', $evento) }}" class="btn btn-outline-warning btn-sm">🛡️ Fila de Moderação</a>
                @endcan
                <span class="text-secondary small">Total no Banco: {{ $evento->perguntas()->count() }}</span>
            </div>
        </div>

        @if(session('sucesso'))
            <div class="alert alert-success">{{ session('sucesso') }}</div>
        @endif

        @error('motivo')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        @forelse($perguntas as $pergunta)
            <div class="card mb-3 shadow-sm border-start border-4 border-primary">
                <div class="card-body">
                    <p class="fs-5 mb-2 text-white">{{ $pergunta->texto }}</p>
                    <p class="text-secondary small mb-2">
                        Perguntado por: <strong>{{ $pergunta->user->name ?? 'Anônimo' }}</strong>
                    </p>
                    <div class="d-flex justify-content-between align-items-center text-secondary small">
                        <span>Status: <span class="badge bg-success">{{ $pergunta->status }}</span></span>
                        <span>{{ $pergunta->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-3">
                        <form action="{{ route('perguntas.votar', $pergunta) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm fw-bold {{ $pergunta->ja_votou ? 'btn-warning' : 'btn-outline-light' }}"
                                    title="{{ $pergunta->ja_votou ? 'Clique para remover seu voto' : 'Clique para votar' }}">
                                👍 {{ $pergunta->ja_votou ? 'Votado' : 'Upvote' }} · {{ $pergunta->votos_count }}
                            </button>
                        </form>

                        @can('delete', $pergunta)
                            <form action="{{ route('perguntas.destroy', $pergunta->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <x-danger-button>Excluir</x-danger-button>
                            </form>
                        @endcan
                    </div>

                    @auth
                        <form action="{{ route('moderacoes.store', $pergunta) }}" method="POST" class="d-flex gap-2 mt-3">
                            @csrf
                            <input type="text" name="motivo" minlength="10" maxlength="255"
                                   class="form-control form-control-sm bg-dark text-white border-secondary"
                                   placeholder="Motivo da denúncia (mín. 10 caracteres)">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Denunciar</button>
                        </form>
                    @endauth
                </div>
            </div>
        @empty
            <div class="alert alert-dark text-center p-4">
                Nenhuma pergunta enviada ainda. Seja o primeiro!
            </div>
        @endforelse

        @if(method_exists($perguntas, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $perguntas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
