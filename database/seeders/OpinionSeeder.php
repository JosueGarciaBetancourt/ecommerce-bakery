<?php

namespace Database\Seeders;

use App\Models\Opinion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpinionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opinion = new Opinion();

        $opinion->opiniontext = "Los pasteles son deliciosos y siempre frescos. ¡Me encantan!";
        $opinion->estrellas = 5;
        $opinion->usuario_id = 2;

        $opinion->save();

        $opinion2 = new Opinion();

        $opinion2->opiniontext = "El servicio es bueno, pero los precios son un poco altos.";
        $opinion2->estrellas = 4;
        $opinion2->usuario_id = 3;

        $opinion2->save();

        $opinion3 = new Opinion();

        $opinion3->opiniontext = "La atención al cliente podría mejorar, pero los productos son aceptables.";
        $opinion3->estrellas = 3;
        $opinion3->usuario_id = 4;

        $opinion3->save();

        $opinion4 = new Opinion();

        $opinion4->opiniontext = "No me gustó la experiencia, los pasteles no estaban frescos.";
        $opinion4->estrellas = 2;
        $opinion4->usuario_id = 5;

        $opinion4->save();
        
    }
}
