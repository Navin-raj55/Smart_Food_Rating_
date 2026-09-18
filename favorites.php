<?php 
session_start();
include 'db_connection.php'; // Include your DB connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Step 1: Fetch favorite product IDs from the database
$favorites = [];
$query = $conn->prepare("SELECT product_id FROM favorite WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
while ($row = $result->fetch_assoc()) {
    $favorites[] = $row['product_id']; // Store product IDs
}
$query->close();

// Step 2: Load products from JSON file
$products_json = file_get_contents('products.json');
$products_data = json_decode($products_json, true);

// Step 3: Prepare an array to hold favorite product details
$favorite_products = [];

// Step 4: Match product IDs with JSON data
foreach ($favorites as $product_id) {
    if (isset($products_data[$product_id])) {
        $favorite_products[$product_id] = $products_data[$product_id];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Favorite Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            color: white;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2.5em;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: fadeIn 2s ease-in-out;
        }
        .back-to-menu {
            text-align: center;
            margin-bottom: 20px;
        }
        .back-to-menu a {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.2em;
            transition: background-color 0.3s ease;
        }
        .back-to-menu a:hover {
            background-color: #0056b3;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-around;
        }
        .product-card {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            width: 300px;
            transition: transform 0.3s, box-shadow 0.3s;
            animation: slideUp 1s ease-in-out;
        }
        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }
        .product-image {
            background-color: white; /* White background for the image container */
            padding: 10px;
        }
        .product-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product-info {
            padding: 15px;
        }
        .product-name {
            font-size: 1.5em;
            color: #ffffff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
        }
        .product-summary {
            margin: 10px 0;
            font-size: 1em;
            color: #dddddd;
        }
        .rating {
            font-size: 1.2em;
            color: #ffeb3b;
        }
        .no-favorites {
            text-align: center;
            color: #ffffff;
            font-size: 1.5em;
            margin-top: 50px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
            animation: fadeIn 2s ease-in-out;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>

<div class="container"> 
    <div class="header">
        <h1>Your Favorite Products</h1>
    </div>

    <div class="back-to-menu">
        <a href="landingpage.php">Back to Menu</a>
    </div>

    <div class="product-grid">
        <?php if (count($favorite_products) > 0): ?>
            <?php foreach ($favorite_products as $product_id => $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?php echo $product['img_path']; ?>" alt="<?php echo $product['name']; ?>">
                </div>
                <div class="product-info">
                    <h2 class="product-name"><?php echo $product['name']; ?></h2>
                    <p class="product-summary"><?php echo $product['summary']; ?></p>
                    <div class="rating">Rating: <?php echo $product['overall_rating']; ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-favorites">You don't have any favorite products yet.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
