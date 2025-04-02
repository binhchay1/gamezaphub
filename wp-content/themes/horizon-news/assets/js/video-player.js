document.addEventListener('DOMContentLoaded', () => {
    const videoPlayers = document.querySelectorAll('.video-player');

    videoPlayers.forEach(player => {
        const video = player.querySelector('.video');
        const playPauseBtn = player.querySelector('.play-pause');
        const volumeBtn = player.querySelector('.volume-btn');
        const volumeBar = player.querySelector('.volume-bar');
        const progressBar = player.querySelector('.progress-bar');
        const currentTime = player.querySelector('.current-time');
        const duration = player.querySelector('.duration');
        const nowPlaying = player.querySelector('.now-playing');

        video.addEventListener('loadedmetadata', () => {
            duration.textContent = formatTime(video.duration);
            progressBar.max = parseInt(video.duration);
        });

        playPauseBtn.addEventListener('click', () => {
            if (video.paused) {
                video.play();
                playPauseBtn.textContent = '⏸';
                nowPlaying.style.display = 'block';
                setTimeout(() => {
                    nowPlaying.style.display = 'none';
                }, 1000);
            } else {
                video.pause();
                playPauseBtn.textContent = '▶';
            }
        });

        volumeBtn.addEventListener('click', () => {
            if (video.muted) {
                video.muted = false;
                video.volume = volumeBar.value;
                updateVolumeIcon(video.volume);
            } else {
                video.muted = true;
                volumeBtn.textContent = '🔇';
            }
        });

        volumeBar.addEventListener('input', () => {
            video.volume = volumeBar.value;
            video.muted = false;
            updateVolumeIcon(video.volume);
        });

        function updateProgress() {
            if (!video.paused && !video.ended) {
                progressBar.value = video.currentTime;
                currentTime.textContent = formatTime(video.currentTime);
            }
            requestAnimationFrame(updateProgress);
        }
        requestAnimationFrame(updateProgress);

        video.addEventListener('ended', () => {
            progressBar.value = progressBar.max;
            currentTime.textContent = formatTime(video.duration);
            playPauseBtn.textContent = '⟲';
        });

        progressBar.addEventListener('input', () => {
            video.currentTime = progressBar.value;
        });

        function updateVolumeIcon(volume) {
            if (volume == 0 || video.muted) {
                volumeBtn.textContent = '🔇';
            } else if (volume < 0.5) {
                volumeBtn.textContent = '🔉';
            } else {
                volumeBtn.textContent = '🔊';
            }
        }

        function formatTime(time) {
            const minutes = Math.floor(time / 60);
            const seconds = Math.floor(time % 60);
            return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        }
    });
});