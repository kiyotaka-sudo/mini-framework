<?php

namespace Database\Models;

use App\Core\Database;
use Database\Models\Model;

class Song extends Model
{
    protected string $table = 'songs';
    protected array $fillable = ['title', 'album_id', 'duration', 'track_number', 'file_url'];
}
