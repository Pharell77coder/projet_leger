document.addEventListener('DOMContentLoaded', function () {
    const favoriteIcons = document.querySelectorAll('.favorite-icon');

    favoriteIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const videoId = icon.getAttribute('data-video-id');

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
                    if (data.action === 'removed') {
                        icon.classList.remove('added');
                        icon.classList.add('removing');

                        setTimeout(() => {
                            icon.parentElement.remove();
                        }, 1000);
                    } else if (data.action === 'added') {
                        icon.classList.add('added');
                        icon.classList.remove('removing');
                    }
                } else {
                    alert(data.message || 'Erreur lors de la modification des favoris.');
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    });
});
