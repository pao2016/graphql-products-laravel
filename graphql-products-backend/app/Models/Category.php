<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Permitir que todos los campos sean asignables masivamente
    protected $guarded = [];

    // Asegura que los timestamps (created_at y updated_at) estén habilitados
    public $timestamps = true;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
