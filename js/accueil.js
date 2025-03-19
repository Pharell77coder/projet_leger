document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".star-rating").forEach(function (rating) {
        const stars = rating.querySelectorAll(".star");
        const videoTitle = rating.getAttribute("data-video");
        const noteInput = document.getElementById("note_" + videoTitle);

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
    });
});
