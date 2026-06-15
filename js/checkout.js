document.addEventListener("DOMContentLoaded", () => {
            
            let cart = getCart();
            

            renderCheckoutSummary();
            togglePaymentMethod('credit_card'); // Default payment method
        });

        // Toggle required fields and visibility based on payment method
        function togglePaymentMethod(method) {
            let ccBlock = document.getElementById("credit-card-fields-block");
            let codBlock = document.getElementById("cod-info-block");
            
            let ccNum = document.getElementById("cc-num");
            let ccExp = document.getElementById("cc-exp");
            let ccCvv = document.getElementById("cc-cvv");

            if (method === "credit_card") {
                ccBlock.style.display = "block";
                codBlock.style.display = "none";
                
                ccNum.required = true;
                ccExp.required = true;
                ccCvv.required = true;
            } else {
                ccBlock.style.display = "none";
                codBlock.style.display = "block";
                
                ccNum.required = false;
                ccExp.required = false;
                ccCvv.required = false;
            }
        }

        // Render mini items list in sidebar
        function renderCheckoutSummary() {
            let cart = getCart();
            let tbody = document.getElementById("checkout-items-list");
            if (!tbody) return;

            tbody.innerHTML = "";
            let subtotal = 0;

            cart.forEach(item => {
                let total = item.price * item.qty;
                subtotal += total;

                let tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.qty}</td>
                    <td style="font-weight: bold;">$${total.toFixed(2)}</td>
                `;
                tbody.appendChild(tr);
            });

            document.getElementById("grand-total-val").textContent = "$" + subtotal.toFixed(2);
        }

        // Handle order placement, validation and alerts
        function handlePlaceOrder(event) {
            event.preventDefault();
            let isValid = true;

            const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            const phoneRegex = /^07\d{8}$/;
            const zipRegex = /^\d{5}$/;

            // Reset error messages
            document.getElementById("email-error").innerHTML = "";
            document.getElementById("phone-error").innerHTML = "";
            document.getElementById("zip-error").innerHTML = "";

            // Validate all fields independently
            if (!emailRegex.test(document.getElementById("email").value)) {
                document.getElementById("email-error").innerHTML = "Enter a valid email address!";
                isValid = false;
            }
            
            if (!phoneRegex.test(document.getElementById("phone").value)) {
                document.getElementById("phone-error").innerHTML = "Enter a valid phone number!";
                isValid = false;
            }
            
            if (!zipRegex.test(document.getElementById("zip-code").value)) {
                document.getElementById("zip-error").innerHTML = "Enter a valid zip code!";
                isValid = false;
            }

            

            // Notify user about order completion details
            if (isValid===true) {
                let cart = getCart();
                let totalCost = 0;
                cart.forEach(item => totalCost += item.price * item.qty);
                let methodLabel = "";
                let selectedRadio = document.querySelector('input[name="pay_method"]:checked');
                if (selectedRadio.value === "cod") {
                    methodLabel = "Cash On Delivery";
                } else {
                    methodLabel = "Credit Card";
                }

                alert(
                    "Order Placed Successfully!\n\n" +                
                    "Total Amount: $" + totalCost.toFixed(2) + "\n" +
                    "Payment Method: " + methodLabel + "\n\n" +
                    "Thank you for shopping at CircuitHub!"
                );
                
                clearCart();
                window.location.href = "index.html";
            }

            return isValid;
        }