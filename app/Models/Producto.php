<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'image',
        'file_uri',
    ];
    /* Esta función establece una relación de 
    uno a muchos con el modelo CartItem.*/
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

}
