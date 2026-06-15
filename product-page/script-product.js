document.addEventListener("DOMContentLoaded", () => {

    // --- 1. Tab Navigation Logic ---
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    if (tabBtns.length > 0) {
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active from all tabs and panes
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));

                // Activate clicked tab
                btn.classList.add('active');

                // Activate matching pane
                const targetId = btn.getAttribute('data-target');
                if (targetId) {
                    const pane = document.getElementById(targetId);
                    if (pane) {
                        pane.classList.add('active');
                    }
                }
            });
        });
    }

    // --- 2. Quantity & Price Calculation Logic ---
    const qtyInput = document.getElementById('qty-input');
    const btnUp = document.getElementById('qty-up');
    const btnDown = document.getElementById('qty-down');
    const priceDisplay = document.getElementById('total-price');

    // FETCH THE DYNAMIC PRICE FROM PHP
    const basePrice = parseFloat(window.dynamicBasePrice) || 0;

    function updatePrice() {
        if (!qtyInput || !priceDisplay) return;

        let currentQty = parseInt(qtyInput.value);

        if (isNaN(currentQty) || currentQty < 1) {
            currentQty = 1;
            qtyInput.value = 1;
        }

        let total = currentQty * basePrice;

        // Ensure standard currency formatting (e.g., 24.00 instead of 24)
        priceDisplay.textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    if (btnUp) {
        btnUp.addEventListener('click', () => {
            qtyInput.value = parseInt(qtyInput.value) + 1;
            updatePrice();
        });
    }

    if (btnDown) {
        btnDown.addEventListener('click', () => {
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
                updatePrice();
            }
        });
    }

    if (qtyInput) {
        qtyInput.addEventListener('change', updatePrice);
    }

    // Initialize price on load
    updatePrice();
});