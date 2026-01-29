<?php

namespace App\Http\Controllers;

use App\Core\Request;
use Database\Models\Album;
use Database\Models\Artist;
use Database\Models\Song;

class SpotifyController
{
    public function index()
    {
        $artists = Artist::make()->all();
        $albums = Album::make()->all();
        
        return view('spotify.index', [
            'title' => 'Spotify Clone',
            'artists' => $artists,
            'albums' => $albums
        ], 'layouts.spotify');
    }

    public function artist(Request $request)
    {
        $id = (int) $request->route('id');
        $artist = Artist::make()->find($id);
        
        // Fetch albums using raw query since we don't have relations yet
        $db = app()->make(\App\Core\Database::class);
        $albums = $db->fetchAll("SELECT * FROM albums WHERE artist_id = ?", [$id]);
        
        return view('spotify.artist', [
            'title' => $artist['name'],
            'artist' => $artist,
            'albums' => $albums
        ], 'layouts.spotify');
    }

    public function album(Request $request)
    {
        $id = (int) $request->route('id');
        $album = Album::make()->find($id);
        $artist = Artist::make()->find($album['artist_id']);
        
        $db = app()->make(\App\Core\Database::class);
        $songs = $db->fetchAll("SELECT * FROM songs WHERE album_id = ? ORDER BY track_number ASC", [$id]);
        
        return view('spotify.album', [
            'title' => $album['name'],
            'album' => $album,
            'artist' => $artist,
            'songs' => $songs
        ], 'layouts.spotify');
    }
}
