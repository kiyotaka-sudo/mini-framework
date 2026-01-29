<?php

namespace Database\Models;

use Database\Models\Model;

class Test1 extends Model
{
    protected static string $table = 'test1s';
    protected array $fillable = ['nom', 'description']; // Default fields for generic test
}