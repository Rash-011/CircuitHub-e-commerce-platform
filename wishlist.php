<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Database connection parameters (matches homePage.php / add_to_wishlist.php)
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

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// 2. Fetch the wishlist rows for this user, joined to products on numeric id.
$wishlist_items = [];
try {
    $query = "SELECT w.id AS wishlist_id, p.id AS real_prod_id, p.name,
                     p.price_from AS price, p.image_path
              FROM wishlist w
              JOIN products p ON w.product_id = p.id
              WHERE w.user_id = :user_id
              ORDER BY w.added_at DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    $wishlist_items = $stmt->fetchAll();
} catch (\PDOException $e) {
    die("Wishlist query failed: " . $e->getMessage());
}

// Helper: build the correct browser-usable image URL.
function product_image_url($db_path)
{
    $relative_path = 'Assests/featured collection/' . basename($db_path);
    return implode('/', array_map('rawurlencode', explode('/', $relative_path)));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist - CircuitHub</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        .wishlist-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            font-family: sans-serif;
        }

        .wishlist-header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .wishlist-header-section h2 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }

        .back-store-btn {
            display: inline-flex;
            align-items: center;
            background-color: #f4f4f7;
            color: #4a5568;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .back-store-btn:hover {
            background-color: #edf2f7;
            color: #1a202c;
            transform: translateX(-3px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .wishlist-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        .wishlist-table th {
            background-color: #343a40 !important;
            color: #ffffff !important;
            padding: 14px 16px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }

        .wishlist-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #eef2f5;
            vertical-align: middle;
            background-color: #fff;
        }

        .product-img {
            width: 65px;
            height: auto;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .remove-btn {
            color: #e53e3e;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .remove-btn:hover {
            background-color: #fff5f5;
            color: #c53030;
        }

        .add-cart-btn {
            background-color: #2f855a;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            transition: background 0.2s;
        }

        .add-cart-btn:hover {
            background-color: #22543d;
        }

        .empty-message {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
            background: #f7fafc;
            border-radius: 8px;
            border: 2px dashed #e2e8f0;
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
                    <li><a href="homePage.php">Home</a></li>
                    <li><a href="wishlist.php" id="nav-wishlist-link" class="active">Wish List</a></li>
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

    <div class="wishlist-container">
        <div class="wishlist-header-section">
            <h2>My Wishlist</h2>
            <a href="homePage.php" class="back-store-btn">⬅ Back to Store Shopping</a>
        </div>

        <?php if (empty($wishlist_items)): ?>
            <div class="empty-message">
                <p style="font-size: 18px; margin-bottom: 15px;">Your wishlist is currently empty.</p>
                <a href="homePage.php" class="add-cart-btn">Explore Components</a>
            </div>
        <?php else: ?>
            <table class="wishlist-table">
                <thead>
                    <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Unit Price</th>
                        <th>Actions</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wishlist_items as $item): ?>
                        <tr id="wishlist-row-<?php echo $item['wishlist_id']; ?>">
                            <td>
                                <img src="<?php echo htmlspecialchars(product_image_url($item['image_path'])); ?>" alt="" class="product-img">
                            </td>
                            <td><strong style="color: #2d3748; font-size: 15px;"><?php echo htmlspecialchars($item['name']); ?></strong></td>
                            <td style="font-weight: 600; color: #4a5568;">$<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                            <td>
                                <a href="add_to_cart.php?product=<?php echo urlencode($item['real_prod_id']); ?>" class="add-cart-btn">Add to Cart</a>
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="remove-btn" onclick="removeItemInline(<?php echo $item['wishlist_id']; ?>)">✕ Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="js/script.js"></script>

    <script>
        function removeItemInline(wishlistId) {
            if (!confirm('Are you sure you want to remove this item from your wishlist?')) {
                return;
            }

            fetch('remove_wishlist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        wishlist_id: wishlistId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const rowElement = document.getElementById('wishlist-row-' + wishlistId);
                        if (rowElement) {
                            rowElement.remove();
                        }

                        const remainingRows = document.querySelectorAll('.wishlist-table tbody tr');
                        if (remainingRows.length === 0) {
                            location.reload();
                        }
                    } else {
                        alert('Could not remove item: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    alert('Communication failure connecting to remove_wishlist.php. Check if file exists.');
                });
        }
    </script>
</body>

</html>