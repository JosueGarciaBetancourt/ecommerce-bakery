<?php

namespace Database\Seeders;

use App\Models\Opinion;
use Illuminate\Database\Seeder;

class OpinionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opiniones = [
            [
                'opiniontext' => "Los pasteles son deliciosos y siempre frescos. ¡Me encantan!",
                'estrellas' => 5,
                'usuario_id' => 2,
                'producto_id' => 1, // 🔹 asigna el producto correspondiente
            ],
            [
                'opiniontext' => "El servicio es bueno, pero los precios son un poco altos.",
                'estrellas' => 4,
                'usuario_id' => 3,
                'producto_id' => 1,
            ],
            [
                'opiniontext' => "La atención al cliente podría mejorar, pero los productos son aceptables.",
                'estrellas' => 3,
                'usuario_id' => 4,
                'producto_id' => 2,
            ],
            [
                'opiniontext' => "No me gustó la experiencia, los pasteles no estaban frescos.",
                'estrellas' => 2,
                'usuario_id' => 5,
                'producto_id' => 3,
            ],
        ];

        foreach ($opiniones as $data) {
            Opinion::create($data);
        }
    }
}
