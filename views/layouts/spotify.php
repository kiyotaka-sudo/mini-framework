<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Spotify Clone' ?></title>
    <link rel="stylesheet" href="/css/spotify.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Optional for icons, using SVGs is better but this is quick -->
</head>
<body>
    <div class="spotify-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <a href="/" class="logo">
                <svg viewBox="0 0 167.5 167.5" width="40px" height="40px" style="fill:white;">
                     <path d="M83.7 0C37.5 0 0 37.5 0 83.7c0 46.3 37.5 83.7 83.7 83.7 46.3 0 83.7-37.5 83.7-83.7S130 0 83.7 0zM122 120.8c-1.4 2.5-4.6 3.2-7.1 1.7-19.6-12-44.3-14.7-73.4-8-2.8.6-5.6-1.2-6.2-4-.6-2.8 1.2-5.6 4-6.2 32.2-7.3 59.6-4.2 81.6 9.3 2.5 1.5 3.4 4.8 1.8 7.3zm10.1-22.5c-1.8 3-5.7 4-8.7 2.1-24.5-15-61.9-19.7-90.8-10.8-3.3 1-6.9-1-7.9-4.3s1-6.9 4.3-7.9c32.8-10.1 73.9-4.9 101.3 12 3 1.8 3.9 5.6 1.8 8.7zm.8-23.3c-29.3-17.4-77.9-19-105.8-10.5-4.2 1.3-8.6-1-9.8-5.3-1.3-4.2 1-8.6 5.3-9.8 32-9.6 85.3-7.9 119.3 12.3 3.8 2.2 5 7.1 2.9 10.9-2.3 3.8-7.2 5-10.9 2.9z"></path>
                </svg>
                Spotify
            </a>
            <div class="nav-links">
                <a href="/" class="nav-item active">
                    <svg viewBox="0 0 24 24" class="nav-icon"><path d="M12.5 3.2l7.5 6.4V21h-6v-6h-3v6h-6V9.6l7.5-6.4zm-.9-2.1L.5 9v13h9v-6h5v6h9V9L11.6 1.1z"></path></svg>
                    Home
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" class="nav-icon"><path d="M10.533 1.279c-5.18 0-9.407 4.14-9.407 9.279s4.226 9.279 9.407 9.279c2.234 0 4.29-.77 5.907-2.058l4.353 4.353a1 1 0 101.414-1.414l-4.344-4.344a9.157 9.157 0 002.077-5.816c0-5.14-4.226-9.28-9.407-9.28zm-7.407 9.279c0-4.006 3.302-7.28 7.407-7.28s7.407 3.274 7.407 7.28-3.302 7.279-7.407 7.279-7.407-3.273-7.407-7.279z"></path></svg>
                    Search
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" class="nav-icon"><path d="M14.5 2.134a1 1 0 011 0l6 3.464a1 1 0 01.5.866V21a1 1 0 01-1 1h-6a1 1 0 01-1-1v-3.464a1 1 0 01.5-.866l6-3.464zM3.5 2.134a1 1 0 011 0l6 3.464a1 1 0 01.5.866V21a1 1 0 01-1 1h-6a1 1 0 01-1-1v-3.464a1 1 0 01.5-.866l6-3.464z"></path></svg>
                    Your Library
                </a>
            </div>
            
            <div style="margin-top: 16px; border-top: 1px solid #282828; padding-top: 16px;">
                <a href="#" class="nav-item">
                    <div style="background:white; color:black; width:24px; height:24px; display:flex; align-items:center; justify-content:center; border-radius:1px;">+</div>
                    Create Playlist
                </a>
                <a href="#" class="nav-item">
                    <div style="background:linear-gradient(135deg,#450af5,#c4efd9); width:24px; height:24px; display:flex; align-items:center; justify-content:center;">♥</div>
                    Liked Songs
                </a>
            </div>
        </nav>

        <!-- Main View -->
        <main class="main-view">
            <header class="top-bar">
                <div class="nav-arrows">
                    <button class="circle-btn"><</button>
                    <button class="circle-btn">></button>
                </div>
                <div class="auth-btns">
                    <a href="/register" style="color:#b3b3b3; text-decoration:none; font-weight:bold;">Sign up</a>
                    <a href="/login" class="btn-primary">Log in</a>
                </div>
            </header>
            
            <?= $slot ?? '' ?>
        </main>
    </div>

    <!-- Player -->
    <div class="player-bar">
        <div class="track-info">
            <div class="track-art"></div> <!-- Placeholder -->
            <div class="track-details">
                <div class="track-name">Nothing Playing</div>
                <div class="track-artist">Select a song</div>
            </div>
        </div>
        
        <div class="player-controls">
            <div class="control-buttons">
                <button class="circle-btn" style="width:28px; height:28px; background:transparent;">⏮</button>
                <button class="play-pause-btn">▶</button>
                <button class="circle-btn" style="width:28px; height:28px; background:transparent;">⏭</button>
            </div>
            <div class="progress-bar-area">
                0:00 <div class="progress-bar"><div class="progress"></div></div> 0:00
            </div>
        </div>
        
        <div class="volume-controls">
            <!-- Add volume slider later -->
        </div>
    </div>
    <script>
        const audioPlayer = new Audio();
        const playBtn = document.querySelector('.play-pause-btn');
        const trackName = document.querySelector('.track-name');
        const trackArtist = document.querySelector('.track-artist');
        const progressBar = document.querySelector('.progress');

        function playTrack(url, title, artist) {
            audioPlayer.src = url;
            trackName.textContent = title;
            trackArtist.textContent = artist;
            audioPlayer.play();
            playBtn.textContent = '⏸';
        }

        playBtn.addEventListener('click', () => {
            if (audioPlayer.paused) {
                audioPlayer.play();
                playBtn.textContent = '⏸';
            } else {
                audioPlayer.pause();
                playBtn.textContent = '▶';
            }
        });

        audioPlayer.addEventListener('timeupdate', () => {
            const percent = (audioPlayer.currentTime / audioPlayer.duration) * 100;
            progressBar.style.width = percent + '%';
        });

        audioPlayer.addEventListener('ended', () => {
            playBtn.textContent = '▶';
            progressBar.style.width = '0%';
        });
    </script>
</body>
</html>
