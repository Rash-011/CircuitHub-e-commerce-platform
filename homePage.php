<?php
// 1. Start the session to track the user
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Database connection parameters
$host    = '127.0.0.1';
$port    = '3307';
$db      = 'circuithub_db';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// 3. Fallback User ID mapping to match your wishlist setup
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// 4. Fetch products from database - ADDED 'description' to the SELECT query here
$product_stmt = $pdo->query("SELECT id, name, price_from, image_path, description FROM products LIMIT 8");
$featured_products = $product_stmt->fetchAll();

// 5. Fetch existing wishlist item IDs
$existing_wishlist = [];
try {
    $wish_stmt = $pdo->prepare("SELECT product_id FROM wishlist WHERE user_id = :user_id");
    $wish_stmt->execute(['user_id' => $user_id]);
    $existing_wishlist = $wish_stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (\PDOException $e) {
    $existing_wishlist = [];
}

// Helper: build the correct browser-usable image URL.
function product_image_url($db_path)
{
    $relative_path = 'Assests/featured collection/' . basename($db_path);
    return implode('/', array_map('rawurlencode', explode('/', $relative_path)));
}

// Helper: is this product id already in the user's wishlist?
function is_wishlisted($product_id, $existing_wishlist)
{
    return in_array((string) $product_id, $existing_wishlist);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CircuitHub - E-Commerce</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        .product-card {
            position: relative;
        }

        .wishlist-heart-link {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 16px;
            color: #ff4d6d;
            z-index: 5;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, background-color 0.2s ease;
            cursor: pointer;
        }

        .wishlist-heart-link:hover {
            transform: scale(1.1);
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .wishlist-heart-link.added {
            background: #ff4d6d !important;
            color: #fff !important;
        }
    </style>
</head>

<body>

    <header class="site-header">
        <div class="main-header">
            <div class="container header-inner">
                <div class="logo">
                    <h1><a href="homePage.php" style="text-decoration: none;"><span class="circuit-text">Circuit</span><span class="hub-text">Hub</span></a></h1>
                </div>
                <div class="header-actions">
                    <a href="register.php">👤 Create an account</a>
                    <a href="login.php">🔒 Login</a>
                    <div class="cart-box" onclick="location.href='cart.php';" style="cursor: pointer;">
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
                        <li><a href="category.php?type=boards">Development Boards</a></li>
                        <li><a href="category.php?type=sensors">Sensors & Modules</a></li>
                        <li><a href="category.php?type=components">Electronic Components</a></li>
                        <li><a href="category.php?type=power">Power & Batteries</a></li>
                        <li><a href="category.php?type=motors">Motors & Actuators</a></li>
                    </ul>
                </div>

                <ul class="nav-links">
                    <li><a href="homePage.php" class="active">Home</a></li>
                    <li><a href="wishlist.php" id="nav-wishlist-link">Wish List</a></li>
                    <li><a href="account.php">My Account</a></li>
                    <li><a href="cart.php">Shopping Cart</a></li>
                    <li><a href="checkout.php">Checkout</a></li>
                </ul>
                <div class="search-box">
                    <form action="search.php" method="GET" style="display: flex; width: 100%;">
                        <input type="text" name="query" placeholder="Search components...">
                        <button type="submit">🔍</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero" style="background: url('Assests/Gemini_Generated_Image_eaxlc5eaxlc5eaxl.png') center/cover;">
            <div class="container hero-text">
                <h2>FIND ELECTRONICS & IOT TOOLS</h2>
                <h3>FOR ALL PROJECT TYPES</h3>
            </div>
        </section>

        <section class="promos container">
            <div class="promo-box">
                <img src="Assests/featured collection/download (26).jpg" alt="Arduino Boards">
                <div class="promo-text">
                    <h3>ARDUINO<span class="highlight"> & DEVELOPMENT BOARDS</span></h3>
                    <p>Explore powerful development boards for coding, automation, robotics, and embedded systems.</p>
                    <a href="category.php?type=boards" class="btn-dark">SHOP ALL BOARDS ></a>
                </div>
            </div>
            <div class="promo-box">
                <img src="Assests/featured collection/Curated - Design Burger.jpg" alt="IoT Modules">
                <div class="promo-text">
                    <h3>IOT<span class="highlight"> & SMART MODULES</span></h3>
                    <p>Build smarter projects with Wi-Fi, Bluetooth, smart sensors, and IoT devices.</p>
                    <a href="category.php?type=sensors" class="btn-orange">EXPLORE IOT PRODUCTS ></a>
                </div>
            </div>
            <div class="promo-box">
                <img src="Assests/featured collection/download (27).jpg" alt="Components">
                <div class="promo-text">
                    <h3>PCB <span class="highlight">& ELECTRONIC COMPONENTS</span></h3>
                    <p>Find PCB boards, resistors, capacitors, modules, and circuit essentials.</p>
                    <a href="category.php?type=components" class="btn-dark">VIEW ALL COMPONENTS ></a>
                </div>
            </div>
        </section>

        <section class="brands container">
            <div class="marquee-container">
                <div class="marquee-track" id="brandTrack">
                    <div class="brand-item" style="display: flex; align-items: center;"><img src="https://upload.wikimedia.org/wikipedia/commons/8/87/Arduino_Logo.svg" alt="Arduino" style="height: 35px; width: auto;"></div>
                    <div class="brand-item" style="display: flex; align-items: center;"><img src="https://upload.wikimedia.org/wikipedia/en/c/cb/Raspberry_Pi_Logo.svg" alt="Raspberry Pi" style="height: 35px; width: auto;"></div>
                    <div class="brand-item" style="display: flex; align-items: center;"><img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Microchip_logo.svg" alt="Microchip" style="height: 35px; width: auto;"></div>
                    <div class="brand-item" style="display: flex; align-items: center;"><img src="https://upload.wikimedia.org/wikipedia/commons/5/50/NXP_Semiconductors_logo_2023.svg" alt="NXP" style="height: 35px; width: auto;"></div>
                    <div class="brand-item; display: flex; align-items: center;"><img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/TexasInstruments-Logo.svg" alt="Texas Instruments" style="height: 35px; width: auto;"></div>
                    <div class="brand-item" style="display: flex; align-items: center;"><img src="https://upload.wikimedia.org/wikipedia/commons/8/8e/Espressif_Logo.svg" alt="Espressif" style="height: 35px; width: auto;"></div>
                </div>
            </div>
        </section>

        <section class="featured-products container">
            <div class="section-header-row">
                <div class="section-title-wrapper">
                    <div class="section-title" style="margin-bottom: 0;">
                        <h3>FEATURED PRODUCTS</h3>
                    </div>
                </div>
            </div>

            <div class="product-grid">
                <?php foreach ($featured_products as $product): ?>
                    <div class="product-card">
                        <div class="wishlist-heart-link<?php echo is_wishlisted($product['id'], $existing_wishlist) ? ' added' : ''; ?>"
                            onclick="toggleWishlist(this, <?php echo (int) $product['id']; ?>)">❤</div>
                        <img src="<?php echo htmlspecialchars(product_image_url($product['image_path'])); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-price-row">
                            <span class="price">$<?php echo htmlspecialchars(number_format($product['price_from'], 2)); ?></span>
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                        </div>
                        <div class="product-actions">
                            <a href="add_to_cart.php?product=<?php echo (int) $product['id']; ?>" class="add-to-cart-btn-link"><button class="add-to-cart-btn">Add to Cart</button></a>

                            <button class="details-btn"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-price="$<?php echo htmlspecialchars(number_format($product['price_from'], 2)); ?>"
                                data-desc="<?php echo htmlspecialchars($product['description']); ?>">Details</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="see-more-container">
                <a href="category.php?type=all" class="see-more-btn">
                    <span>See More Products</span>
                    <span class="btn-arrow">➔</span>
                </a>
            </div>
        </section>

        <script>
            function toggleWishlist(element, productId) {
                fetch('add_to_wishlist.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            product: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = 'wishlist.php';
                        } else {
                            alert('Could not update Wishlist: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error handling request:', error);
                    });
            }
        </script>

        <div id="productModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2 id="modalName">Product Name</h2>
                <h4 id="modalPrice" style="color: #ff6600; margin: 10px 0;">$0.00</h4>
                <p id="modalDesc">Product Description will pop up right here.</p>
            </div>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>

</html>