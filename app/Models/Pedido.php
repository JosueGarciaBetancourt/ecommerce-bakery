<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total'];

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
