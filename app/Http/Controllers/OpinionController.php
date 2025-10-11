<?php

namespace App\Http\Controllers;

use App\Models\Opinion;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    public function index(Request $request)
    {
        //
    }   

    public function store(Request $request)
    {
        // Validación de los campos
        $validatedData = $request->validate([
            'opiniontext' => 'required|string|min:10|max:1000',
            'estrellas' => 'required|integer|min:1|max:5',
        ]);

        // Crear nueva opinión con los datos validados
        $opinion = new Opinion();
        $opinion->opiniontext = $validatedData['opiniontext'];
        $opinion->estrellas = $validatedData['estrellas'];
        $opinion->usuario_id = auth()->id(); // Asocia la opinión al usuario autenticado

        $opinion->save();
        notify()->success('Opinión agregada correctamente!');
        return redirect()->route('home')->with('success', 'Opinión enviada correctamente.');
    }

}
