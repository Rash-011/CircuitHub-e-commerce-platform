// js/script.js

document.addEventListener("DOMContentLoaded", () => {

    // Smooth Infinite Marquee Logic
    const brandTrack = document.getElementById('brandTrack');

    // We clone the inner HTML of the track and append it to itself.
    // This doubles the length of the content.
    // Because our CSS animation translates exactly -50% horizontally,
    // the end of the clone lines up perfectly with the start of the original,
    // creating a seamless, infinite loop.
    if (brandTrack) {
        const clone = brandTrack.innerHTML;
        brandTrack.innerHTML += clone;
    }

    // Categories Dropdown Logic
    const categoriesBtn = document.getElementById('categoriesBtn');
    const categoriesDropdown = document.getElementById('categoriesDropdown');

    if (categoriesBtn && categoriesDropdown) {
        categoriesBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            categoriesDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking anywhere else on the page
        document.addEventListener('click', (e) => {
            if (!categoriesDropdown.contains(e.target) && e.target !== categoriesBtn) {
                categoriesDropdown.classList.remove('show');
            }
        });
    }

});