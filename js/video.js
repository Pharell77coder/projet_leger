document.addEventListener("DOMContentLoaded", function () {
    const favoriteIcon = document.getElementById("favorite-icon");

    if (favoriteIcon) {
        favoriteIcon.addEventListener("click", function () {
            const videoId = this.getAttribute("data-video-id");

            fetch("video.php?id=" + videoId, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "toggle_fav=1"
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    favoriteIcon.classList.toggle("added");
                } else {
                    alert("Erreur : " + data.message);
                }
            })
            .catch(error => {
                console.error("Erreur :", error);
            });
        });
    }
});

const stars = document.querySelectorAll(".star");
const noteInput = document.getElementById("note");

stars.forEach(star => {
    star.addEventListener("click", function () {
        let value = this.getAttribute("data-value");
        noteInput.value = value;

        stars.forEach(s => s.classList.remove("selected"));
        for (let i = 0; i < value; i++) {
            stars[i].classList.add("selected");
        }
    });
});
