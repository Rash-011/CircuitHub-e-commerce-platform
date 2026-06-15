<?php
// 1. Connect to the database
require_once 'db.php';

// 2. Safely check if an ID was passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<h1>Error: Product ID is missing.</h1>");
}
$product_id = $_GET['id'];

// 3. TRY to ask the database for the info
try {
    // This query is custom-built to match your screenshot perfectly
    $sql = "SELECT p.*, d.sku, d.brand, d.description AS detailed_description, d.specifications, d.stock 
            FROM products p 
            LEFT JOIN product_details d ON p.id = d.product_id 
            WHERE p.id = :id LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("<h1>Error 404: Product not found.</h1>");
    }

} catch (PDOException $e) {
    // 4. IF THE DATABASE CRASHES, SHOW THE EXACT ERROR!
    die("<div style='padding: 40px; font-family: sans-serif;'>
            <h1 style='color: red;'>Database Crash Detected!</h1>
            <p><strong>The server said:</strong> " . $e->getMessage() . "</p>
         </div>");
}

// 5. Fetch related products (same category, exclude current product)
try {
    $related_sql = "SELECT * FROM products 
                    WHERE category = :category AND id != :id 
                    ORDER BY RAND() LIMIT 4";
    $related_stmt = $pdo->prepare($related_sql);
    $related_stmt->execute([
        'category' => $product['category'],
        'id' => $product_id
    ]);
    $related_products = $related_stmt->fetchAll(PDO::FETCH_ASSOC);

    // If fewer than 4 in same category, fill remaining slots with random products
    if (count($related_products) < 4) {
        $existing_ids = array_merge([$product_id], array_column($related_products, 'id'));
        $placeholders = implode(',', array_fill(0, count($existing_ids), '?'));
        $fill_sql = "SELECT * FROM products 
                     WHERE id NOT IN ($placeholders) 
                     ORDER BY RAND() LIMIT " . (4 - count($related_products));
        $fill_stmt = $pdo->prepare($fill_sql);
        $fill_stmt->execute($existing_ids);
        $related_products = array_merge($related_products, $fill_stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (PDOException $e) {
    $related_products = []; // Fail silently for related products
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - CircuitHub</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header class="site-header">
        <div class="main-header">
            <div class="container header-inner">
                <div class="logo">
                    <h1><span class="circuit-text">Circuit</span><span class="hub-text">Hub</span></h1>
                </div>
                <div class="header-actions">
                    <a href="#">👤 Create an account</a>
                    <a href="#">🔒 Login</a>
                    <div class="cart-box">
                        <span class="cart-icon">🛒</span>
                        <strong>CART: 0</strong>
                    </div>
                </div>
            </div>
        </div>

        <nav class="main-nav">
            <div class="container nav-inner">
                <div class="categories-wrapper">
                    <div class="categories-btn" id="categoriesBtn">CATEGORIES ☰</div>
                    <ul class="categories-dropdown" id="categoriesDropdown">
                        <li><a href="/CircuitHub-ecommerce/product-page/productImages/products.html">All Products</a></li>
                        <li><a href="#">Development Boards</a></li>
                        <li><a href="#">Sensors & Modules</a></li>
                        <li><a href="#">Electronic Components</a></li>
                        <li><a href="#">Power & Batteries</a></li>
                        <li><a href="#">Motors & Actuators</a></li>
                    </ul>
                </div>
                <ul class="nav-links">
                    <li><a href="/CircuitHub-ecommerce/index.html">Home</a></li>
                    <li><a href="#">Wish List (0)</a></li>
                    <li><a href="#">My Account</a></li>
                    <li><a href="#">Shopping Cart</a></li>
                    <li><a href="#">Checkout</a></li>
                </ul>
                <div class="search-box">
                    <input type="text" placeholder="Search...">
                    <button type="submit">🔍</button>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-container">
        <div class="product-top-section">
            
            <div class="gallery-column">
                <div class="price-header">
                    <p class="sku">SKU: <?= htmlspecialchars($product['sku'] ?? 'N/A') ?></p>
                    <div class="price-row">
                        <h1>Total: $<span id="total-price"><?= number_format($product['price_from'], 2) ?></span></h1>
                    </div>
                    <p class="volume-pricing">Ships within 24 hours • Standard Delivery</p>
                </div>

                <div class="main-image-container">
                    <button class="expand-btn"><i class="fa-solid fa-expand"></i></button>
                    <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="main-image" onerror="this.src='https://via.placeholder.com/600?text=No+Image'">
                </div>

                <div class="gallery-controls">
                    <button class="reset-btn"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                    <div class="thumbnail-track">
                        <i class="fa-solid fa-chevron-left nav-arrow"></i>
                        <div class="thumbnails">
                            <div class="thumb active"><img src="<?= htmlspecialchars($product['image_path']) ?>" alt="Thumb 1"></div>
                            <div class="thumb"><img src="https://via.placeholder.com/60/eeeeee/333333?text=2" alt="Thumb 2"></div>
                            <div class="thumb"><img src="https://via.placeholder.com/60/eeeeee/333333?text=3" alt="Thumb 3"></div>
                        </div>
                        <i class="fa-solid fa-chevron-right nav-arrow"></i>
                    </div>
                </div>
            </div>

            <div class="config-column">
                <div class="top-actions">
                    <button><i class="fa-solid fa-reply"></i></button>
                    <button><i class="fa-solid fa-share"></i></button>
                    <button><i class="fa-regular fa-heart"></i></button>
                </div>

                <div class="variant-header">
                    <div class="variant-info">
                        <span class="label">PRODUCT DETAILS</span>
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                    </div>
                </div>

                <div class="static-info-box">
                    <div class="box-header">
                        <h4>Description</h4>
                    </div>
                    <div class="box-content">
                        <p class="brand-name"><strong>Brand:</strong> <?= htmlspecialchars($product['brand'] ?? 'CircuitMaker') ?></p>
                        <p class="desc-text"><?= nl2br(htmlspecialchars($product['detailed_description'] ?? $product['description'])) ?></p>
                    </div>
                </div>

                <div class="static-info-box">
                    <div class="box-header">
                        <h4>Availability</h4>
                    </div>
                    <div class="box-content">
                        <?php if (isset($product['stock']) && $product['stock'] > 0): ?>
                            <div class="availability-badge in-stock">
                                <span class="pulse-dot"></span>
                                <span class="status-text">In Stock (<?= $product['stock'] ?> Available)</span>
                            </div>
                        <?php else: ?>
                            <div class="availability-badge out-of-stock">
                                <i class="fa-solid fa-xmark"></i>
                                <span class="status-text">Out of Stock</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="config-footer">
                    <div class="qty-selector">
                        <input type="number" id="qty-input" value="1" min="1">
                        <div class="qty-controls">
                            <button id="qty-up"><i class="fa-solid fa-caret-up"></i></button>
                            <button id="qty-down"><i class="fa-solid fa-caret-down"></i></button>
                        </div>
                    </div>
                    <button class="finish-btn">Add to Cart <i class="fa-solid fa-cart-shopping"></i></button>
                </div>
            </div>
        </div>

        <div class="tabs-section">
            <div class="tab-headers">
                <button class="tab-btn active" data-target="specs">Technical Specifications</button>
                <button class="tab-btn" data-target="reviews">Reviews and Ratings</button>
                <button class="tab-btn" data-target="included">What's Included</button>
            </div>
            <div class="tab-content-area">
                
                <div class="tab-pane active" id="specs">
                    <ul class="info-list">
                        <?= $product['specifications'] ?? '<li>Specifications are currently being updated.</li>' ?>
                    </ul>
                </div>
                
                <div class="tab-pane" id="reviews">
                    <div style="padding: 10px 15px;">
                        <p style="font-size: 13px; color: #777;">No reviews yet. Be the first to review!</p>
                    </div>
                </div>
                
                <div class="tab-pane" id="included">
                    <ul class="info-list" style="padding-left: 15px;">
                        <li>1x <?= htmlspecialchars($product['name']) ?></li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="related-section">
            <h3>Related Products</h3>
            <div class="product-grid">
                
                <?php if (!empty($related_products)): ?>
                    <?php foreach ($related_products as $rel): ?>
                        <a href="/CircuitHub-ecommerce/product-page/product.php?id=<?= $rel['id'] ?>" class="rel-card" style="text-decoration: none; color: inherit;">
                            <div class="rel-img">
                                <img src="<?= htmlspecialchars($rel['image_path']) ?>" 
                                     alt="<?= htmlspecialchars($rel['name']) ?>" 
                                     onerror="this.src='https://via.placeholder.com/200x250/f8f8f8/333333?text=No+Image'">
                            </div>
                            <div class="rel-info">
                                <h4><?= htmlspecialchars($rel['name']) ?></h4>
                                <p>FROM $<?= number_format($rel['price_from'], 2) ?></p>
                                <button class="fav-btn"><i class="fa-regular fa-heart"></i></button>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #777; font-size: 13px;">No related products found.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script>
        window.dynamicBasePrice = <?= $product['price_from'] ?? 0 ?>;
    </script>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-col">
                <h4>INFORMATION</h4>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Delivery</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>CUSTOMER SERVICE</h4>
                <ul>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Site Map</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>EXTRAS</h4>
                <ul>
                    <li><a href="#">Brands</a></li>
                    <li><a href="#">Gift Vouchers</a></li>
                    <li><a href="#">Affiliates</a></li>
                    <li><a href="#">Specials</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>MY ACCOUNT</h4>
                <ul>
                    <li><a href="#">My Account</a></li>
                    <li><a href="#">Order History</a></li>
                    <li><a href="#">Wish List</a></li>
                    <li><a href="#">Newsletter</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>FOLLOW US</h4>
                <div class="social-icons">
                    <a href="#" class="social-icon fb">F</a>
                    <a href="#" class="social-icon tw">T</a>
                    <a href="#" class="social-icon rss">R</a>
                </div>
                <p class="copyright">Powered By OpenCart &copy; 2014</p>
            </div>
        </div>
    </footer>

    <script src="../js/script.js"></script>
    <script src="script-product.js"></script>
</body>
</html>