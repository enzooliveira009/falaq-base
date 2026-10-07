<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Pergunta;
use App\Http\Requests\StorePerguntaRequest;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::all();
        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        return view('eventos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
        ]);

        Evento::create([
            'titulo' => $request->input('titulo'),
            'descricao' => $request->input('descricao'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('eventos.index')
            ->with('sucesso', 'Evento criado com sucesso!');
    }

    public function show($id)
    {
        $evento = Evento::findOrFail($id);

        $perguntas = Pergunta::where('evento_id', $evento->id)
            ->where('is_public', true)
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('eventos.show', compact('evento', 'perguntas'));
    }

    public function storePergunta(StorePerguntaRequest $request, $id)
    {
        $evento = Evento::findOrFail($id);

        Pergunta::create([
            'evento_id' => $evento->id,
            'texto'     => $request->input('texto'),
            'status'    => 'pendente',
            'user_id'   => auth()->id(),
        ]);

        return redirect()->route('eventos.show', $evento->id)
            ->with('sucesso', 'Sua pergunta foi enviada com sucesso!');
    }

    public function destroyPergunta(Pergunta $pergunta)
    {
        $this->authorize('delete', $pergunta);

        $evento = $pergunta->evento;

        $pergunta->delete();

        return redirect()->route('eventos.show', $evento->id)
            ->with('sucesso', 'Pergunta removida com sucesso!');
    }
}
