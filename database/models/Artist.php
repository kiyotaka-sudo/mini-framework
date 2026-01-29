<?php

namespace Database\Models;

use App\Core\Database;
use Database\Models\Model;

class Artist extends Model
{
    protected string $table = 'artists';
    protected array $fillable = ['name', 'bio', 'image_url'];
}
