<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $producto = new Producto();

        $producto->name = "Torta de Chocolate";
        $producto->description = "Deliciosa torta bañada en chocolate negro";
        $producto->category = "Tortas";
        $producto->price = 23;
        $producto->file_uri = "images/torta_chocolate.jpg";
        $producto->save();

        $producto2 = new Producto();

        $producto2->name = "Torta de 3 leches";
        $producto2->description = "Deliciosa torta bañada en 3 leches";
        $producto2->category = "Tortas";
        $producto2->price = 20;
        $producto2->file_uri = "images/torta_3leches.jpg";

        $producto2->save();

        $producto3 = new Producto();

        $producto3->name = "Tarta de Fresa";
        $producto3->description = "Deliciosa torta rellena de fresas";
        $producto3->category = "Tartas";
        $producto3->price = 18;
        $producto3->file_uri = "images/tarta_fresa.jpg";

        $producto3->save();

        $producto4 = new Producto();

        $producto4->name = "Cupcake";
        $producto4->description = "Deliciosos cupcakes de distintos sabores";
        $producto4->category = "Cupcakes";
        $producto4->price = 5;
        $producto4->file_uri = "images/cupcakes.jpg";

        $producto4->save();

        $producto5 = new Producto();

        $producto5->name = "Cheesecake de Maracuya";
        $producto5->description = "Suave cheesecake de maracuya";
        $producto5->category = "Cheesecake";
        $producto5->price = 21;
        $producto5->file_uri = "images/cheesecake_maracuya.webp";

        $producto5->save();

        $producto6 = new Producto();
        $producto6->name = "Cheesecake de Fresa";
        $producto6->description = "Suave cheesecake de fresa";
        $producto6->category = "Cheesecake";
        $producto6->price = 21;
        $producto6->file_uri = "images/cheesecake_fresa.webp";

        $producto6->save();
        
    }
}
