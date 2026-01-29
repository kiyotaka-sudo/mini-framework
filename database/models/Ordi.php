<?php

namespace Database\Models;

use Database\Models\Model;

class Ordi extends Model
{
    protected string $table = 'ordis';
    protected array $fillable = ['iesy', 'qwerty'];
}