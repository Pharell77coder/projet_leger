document.addEventListener('DOMContentLoaded', function () {
    const favoriteIcon = document.getElementById('favorite-icon');

    if (favoriteIcon) {
        favoriteIcon.addEventListener('click', function () {
            const videoId = favoriteIcon.getAttribute('data-video-id');

            fetch('add_to_favorites.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ video_id: videoId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    favoriteIcon.classList.toggle('added');
                } else {
                    alert("Erreur lors de l'ajout aux favoris.");
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    }
});
