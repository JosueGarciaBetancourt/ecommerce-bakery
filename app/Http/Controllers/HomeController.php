<?php

namespace App\Http\Controllers;

use App\Models\Opinion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $opiniones = Opinion::with('user')->get(); // Cargar opiniones con usuarios relacionados
        return view('home', compact('opiniones'));
    }

    public function inicio()
    {
        $opiniones = Opinion::with('user')->get();
        return view('home', compact('opiniones'));
    }
}
