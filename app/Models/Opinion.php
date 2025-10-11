<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Opinion extends Model
{
    use HasFactory;

    protected $fillable = ['opiniontext', 'estrellas', 'usuario_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
