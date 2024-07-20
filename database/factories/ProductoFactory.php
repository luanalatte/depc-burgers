<?php

namespace Database\Factories;

use App\Entidades\Categoria;
use App\Entidades\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $number = 1;

        return [
            'nombre' => 'Producto ' . $number++,
            'fk_idcategoria' => Categoria::select('idcategoria')->inRandomOrder()->first()->idcategoria,
            'cantidad' => random_int(100, 2000),
            'precio' => random_int(200000, 600000) / 100,
        ];
    }
}
