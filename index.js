const maxSlider = document.getElementById("maxPrice");
const minSlider = document.getElementById("minPrice");
const minPriceLabel = document.getElementById("price-min");
const maxPriceLabel = document.getElementById("price-max");
const filterForm = document.getElementById("filterForm");

let timeout = null;

function submitFormWithDelay() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        filterForm.submit();
    }, 500); // Délai de 500ms avant soumission
}

minSlider.addEventListener("input", function () {
    if (parseFloat(minSlider.value) > parseFloat(maxSlider.value)) {
        maxSlider.value = minSlider.value;
    }
    minPriceLabel.textContent = parseFloat(minSlider.value).toFixed(2);
    submitFormWithDelay();
});

maxSlider.addEventListener("input", function () {
    if (parseFloat(maxSlider.value) < parseFloat(minSlider.value)) {
        minSlider.value = maxSlider.value;
    }
    maxPriceLabel.textContent = parseFloat(maxSlider.value).toFixed(2);
    submitFormWithDelay();
});

document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener("change", function () {
        submitFormWithDelay();
    });
});
