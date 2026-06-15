// js/cart.js

// 1. Read cart items array from localStorage
function getCart() {
    let cart = localStorage.getItem("cart");
    return cart ? JSON.parse(cart) : [];
}

// 2. Save cart items array to localStorage and update header
function saveCart(cart) {
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartHeader();
    
    // Trigger update on cart page if active
    if (typeof renderCart === "function") {
        renderCart();
    }
    // Trigger update on checkout page summary if active
    if (typeof renderCheckoutSummary === "function") {
        renderCheckoutSummary();
    }
}

// 3. Add a product to the cart
function addToCart(name, price, img) {
    let cart = getCart();
    
    // Resolve relative path to absolute URL
    let absoluteImg = new URL(img, window.location.href).href;
    
    // Check if product is already in the cart
    let existingItem = cart.find(item => item.name === name);
    
    if (existingItem) {
        existingItem.qty++;
    } else {
        cart.push({
            name: name,
            price: parseFloat(price),
            img: absoluteImg,
            qty: 1
        });
    }
    
    saveCart(cart);
    alert(name + " has been added to your cart!");
}

// 4. Update the cart count and cost in the header
function updateCartHeader() {
    let cart = getCart();
    let totalQty = 0;
    let totalCost = 0;
    
    cart.forEach(item => {
        totalQty += item.qty;
        totalCost += item.price * item.qty;
    });
    
    let cartBox = document.querySelector(".cart-box strong");
    if (cartBox) {
        cartBox.textContent = "CART: " + totalQty + " ($" + totalCost.toFixed(2) + ")";
    }
}

// 5. Change product quantity (+1 or -1) by index
function updateQty(index, change) {
    let cart = getCart();
    if (cart[index]) {
        cart[index].qty += change;
        if (cart[index].qty <= 0) {
            cart.splice(index, 1); // Remove item if quantity becomes 0
        }
        saveCart(cart);
    }
}

// 6. Delete a product by index
function removeItem(index) {
    let cart = getCart();
    if (cart[index]) {
        cart.splice(index, 1);
        saveCart(cart);
    }
}

// 7. Wipe the cart clear
function clearCart() {
    localStorage.removeItem("cart");
    updateCartHeader();
}

// Run header update when any page loading finishes
document.addEventListener("DOMContentLoaded", () => {
    updateCartHeader();
});
