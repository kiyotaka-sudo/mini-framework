<?php

namespace Database\Models;

class Client extends Model
{
    protected string $table = 'clients';
    protected array $fillable = ['nomp', 'age_p']; // Fixed space in field name if it was age_p
}