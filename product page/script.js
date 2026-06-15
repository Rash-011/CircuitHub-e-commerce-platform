// script.js

// We will store the database data here once it loads
let globalProducts = [];

document.addEventListener("DOMContentLoaded", () => {
    const gridContainer = document.getElementById("product-grid");
    const resultsCount = document.getElementById("results-count");
    const checkboxes = document.querySelectorAll(".filter-check");
    
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
            applyFilters();        // Render the products based on checkboxes

        } catch (error) {
            console.error("Failed to load products:", error);
            resultsCount.textContent = "Error loading products.";
        }
    }

    // 2. Render HTML to the screen
    function renderProducts(productsToRender) {
        gridContainer.innerHTML = ""; // Clear the grid
        resultsCount.textContent = `Results: ${productsToRender.length} Variants`;

        productsToRender.forEach(product => {
            // Build the HTML using the data from MySQL
            const cardHtml = `
                <div class="product-card">
                    <div class="image-wrapper">
                        <img src="${product.image_path}" alt="${product.name}" onerror="this.src='https://via.placeholder.com/200?text=No+Image'">
                    </div>
                    
                    <div class="product-info">
                        <h4>${product.name}</h4>
                        <p class="price">FROM $${parseFloat(product.price_from).toFixed(2)}</p>
                    </div>

                    <div class="extended-details">
                        <p class="desc">${product.description}</p>
                        <button class="add-to-cart-btn" onclick="addToCart('${product.name}', ${parseFloat(product.price_from)}, '${product.image_path}')">Add to Cart</button>
                    </div>
                </div>
            `;
            gridContainer.insertAdjacentHTML('beforeend', cardHtml);
        });
    }

    // 3. Instant Filtering Logic (No Refresh)
    function applyFilters() {
        // Find out which categories the user clicked
        const checkedValues = Array.from(checkboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        // Filter the database array instantly in the browser
        const filteredData = globalProducts.filter(product => {
            // Check if the product's category OR connectivity matches a checked box
            return checkedValues.includes(product.category) || checkedValues.includes(product.connectivity);
        });

        renderProducts(filteredData);
    }

    // 4. Listen for checkbox clicks
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });

    // Start the process when the page loads!
    loadProducts();
});