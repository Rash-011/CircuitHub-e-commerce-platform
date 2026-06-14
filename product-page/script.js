// script.js

// We will store the database data here once it loads
let globalProducts = [];

// ---> NEW ADDITION: Pagination Variables <---
let currentFilteredProducts = []; // Stores the currently filtered list
let currentPage = 1;
const itemsPerPage = 6; // Maximum cards per page
// ---> END NEW ADDITION <---

document.addEventListener("DOMContentLoaded", () => {
    const gridContainer = document.getElementById("product-grid");
    const resultsCount = document.getElementById("results-count");
    const checkboxes = document.querySelectorAll(".filter-check");

    // ---> NEW ADDITION: Grab the slider elements from the DOM <---
    const priceSlider = document.getElementById("price-slider");
    const priceValueDisplay = document.getElementById("price-value");
    // ---> END NEW ADDITION <---

    // Grab our new pagination container
    const paginationContainer = document.getElementById("pagination-controls");

    // Logic for "All Products" category checkbox
    const filterAllCategory = document.getElementById("filter-all-category");
    if (filterAllCategory) {
        const categoryCheckboxes = filterAllCategory.closest('.filter-group').querySelectorAll('.filter-check');

        filterAllCategory.addEventListener('change', (e) => {
            categoryCheckboxes.forEach(cb => cb.checked = e.target.checked);
            applyFilters();
        });

        categoryCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const allChecked = Array.from(categoryCheckboxes).every(c => c.checked);
                filterAllCategory.checked = allChecked;
            });
        });
    }
    // 1. Fetch data from PHP database API
    async function loadProducts() {
        try {
            const response = await fetch('../api.php');
            const data = await response.json();

            if (data.error) {
                console.error("Database Error:", data.error);
                return;
            }

            globalProducts = data; // Save the database array

            // ---> NEW ADDITION: Dynamically configure the slider based on database <---
            // Find the highest price in the database array
            const highestPrice = Math.max(...globalProducts.map(p => parseFloat(p.price_from)));

            // Round up to the nearest whole number for a clean slider
            const maxSliderLimit = Math.ceil(highestPrice);

            // Set the slider's max limit and current value to the highest price
            priceSlider.max = maxSliderLimit;
            priceSlider.value = maxSliderLimit;
            priceValueDisplay.textContent = `$${maxSliderLimit}`;
            // ---> END NEW ADDITION <---

            applyFilters();        // Render the products based on checkboxes

        } catch (error) {
            console.error("Failed to load products:", error);
            resultsCount.textContent = "Error loading products.";
        }
    }

    // ---> NEW ADDITION: Slicing the array for Pagination <---
    function displayCurrentPage() {
        // Calculate where to start and end the slice based on the current page
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        // Grab only the 6 products for the current page
        const productsToShow = currentFilteredProducts.slice(startIndex, endIndex);

        // Update the results text to show the total found, not just the 6 on screen
        resultsCount.textContent = `Results: ${currentFilteredProducts.length} Variants`;

        // Render those 6 products
        renderProducts(productsToShow);

        // Render the Next/Prev buttons
        renderPaginationControls();
    }
    // ---> END NEW ADDITION <---

    function renderProducts(productsToRender) {
        gridContainer.innerHTML = "";

        productsToRender.forEach(product => {
            const cardHtml = `
                <div class="product-card">
                    <div class="image-wrapper">
                        <img src="../${product.image_path}" alt="${product.name}" onerror="this.onerror=null; this.src='https://via.placeholder.com/200?text=No+Image'">
                    </div>
                    
                    <div class="product-info">
                        <h4>${product.name}</h4>
                        <p class="price">FROM $${parseFloat(product.price_from).toFixed(2)}</p>
                    </div>

                    <div class="extended-details">
                        <p class="desc">${product.description}</p>
                        <button class="add-to-cart-btn">Details</button>
                    </div>
                </div>
            `;
            gridContainer.insertAdjacentHTML('beforeend', cardHtml);
        });
    }

    // ---> NEW ADDITION: Creating the Next/Prev Buttons <---
    function renderPaginationControls() {
        // Calculate total pages needed
        const totalPages = Math.ceil(currentFilteredProducts.length / itemsPerPage);

        // If there's only 1 page (or 0 results), hide the buttons
        if (totalPages <= 1) {
            paginationContainer.innerHTML = "";
            return;
        }

        // Build the buttons. If on page 1, disable "Prev". If on last page, disable "Next".
        paginationContainer.innerHTML = `
            <button id="prev-btn" class="page-btn" ${currentPage === 1 ? 'disabled' : ''}>&laquo; Previous</button>
            <span class="page-info">Page ${currentPage} of ${totalPages}</span>
            <button id="next-btn" class="page-btn" ${currentPage === totalPages ? 'disabled' : ''}>Next &raquo;</button>
        `;

        // Attach click events to the new buttons
        if (currentPage > 1) {
            document.getElementById('prev-btn').addEventListener('click', () => {
                currentPage--;
                displayCurrentPage();
                window.scrollTo({ top: 0, behavior: 'smooth' }); // Optional: Scroll up smoothly
            });
        }

        if (currentPage < totalPages) {
            document.getElementById('next-btn').addEventListener('click', () => {
                currentPage++;
                displayCurrentPage();
                window.scrollTo({ top: 0, behavior: 'smooth' }); // Optional: Scroll up smoothly
            });
        }
    }
    // ---> END NEW ADDITION <---

    // 3. Instant Filtering Logic (No Refresh)
    function applyFilters() {
        // Find out which categories the user clicked
        const checkedValues = Array.from(checkboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        // ---> NEW ADDITION: Get the current value of the slider <---
        const maxAllowedPrice = parseFloat(priceSlider.value);
        // ---> END NEW ADDITION <---

        // Save the filtered results to our new global variable
        currentFilteredProducts = globalProducts.filter(product => {
            // Check checkboxes
            const matchesCheckboxes = checkedValues.includes(product.category) || checkedValues.includes(product.connectivity);

            // ---> NEW ADDITION: Check if product price is less than or equal to slider value <---
            const productPrice = parseFloat(product.price_from);
            const matchesPrice = productPrice <= maxAllowedPrice;
            // ---> END NEW ADDITION <---

            // ---> NEW ADDITION: Combine both conditions <---
            return matchesCheckboxes && matchesPrice;
            // ---> END NEW ADDITION <---
        });

        // ---> NEW ADDITION: Reset to page 1 whenever a filter changes <---
        currentPage = 1;
        displayCurrentPage();
        // ---> END NEW ADDITION <---
    }

    // 4. Listen for checkbox clicks
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });

    // ---> NEW ADDITION: Debounce Timer <---
    let debounceTimer;

    priceSlider.addEventListener('input', () => {
        // 1. Update the text display instantly so the slider feels responsive
        priceValueDisplay.textContent = `$${priceSlider.value}`;

        // 2. Cancel the previous timer if the user is still actively dragging
        clearTimeout(debounceTimer);

        // 3. Set a new timer. It waits 150 milliseconds after they stop dragging 
        // to actually run the heavy lifting and redraw the images.
        debounceTimer = setTimeout(() => {
            applyFilters();
        }, 150);
    });


    // Start the process when the page loads!
    loadProducts();
});