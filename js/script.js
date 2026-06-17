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

    // ==========================================
    // Product Details Modal Logic
    // ==========================================
    const modal = document.getElementById("productModal");
    const closeBtn = document.querySelector(".close-btn");
    const modalName = document.getElementById("modalName");
    const modalPrice = document.getElementById("modalPrice");
    const modalDesc = document.getElementById("modalDesc");

    if (modal && closeBtn) {
        // Capture click events on all Details buttons
        document.querySelectorAll(".details-btn").forEach(button => {
            button.addEventListener("click", function(e) {
                e.preventDefault(); // Stop any anchor page jumps
                
                // Extract description details from clicked button dataset attributes
                const name = this.getAttribute("data-name");
                const price = this.getAttribute("data-price");
                const desc = this.getAttribute("data-desc");

                // Inject structured values into the modal box text containers
                modalName.textContent = name;
                modalPrice.textContent = price;
                modalDesc.textContent = desc;

                // Turn on the modal visibility
                modal.style.display = "flex";
            });
        });

        // Close the text box when clicking the close (X) button
        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        // Close the text box automatically if clicking outside the center frame window layout area
        window.addEventListener("click", (event) => {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    }

});