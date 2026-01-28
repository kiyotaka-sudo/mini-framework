<?php

namespace Database\Models;

class Produit extends Model
{
    protected string $table = 'produits';
    protected array $fillable = ['prix', 'nom'];
}