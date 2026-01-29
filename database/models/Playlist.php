<?php

namespace Database\Models;

use Database\Models\Model;

class Playlist extends Model
{
    protected string $table = 'playlists';
    protected array $fillable = ['name', 'description'];
}