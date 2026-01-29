<?php

namespace Database\Models;

use App\Core\Database;
use Database\Models\Model;

class Album extends Model
{
    protected string $table = 'albums';
    protected array $fillable = ['name', 'artist_id', 'release_date', 'cover_url'];
}
