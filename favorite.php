    <?php
    session_start();

    // Database connection
    $host = 'localhost'; // Database host
    $db = 'sfr'; // Database name
    $user = 'root'; // Database username
    $pass = ''; // Database password

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // User login check
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);

        // Assume password hashing is used during registration
        $result = $conn->query("SELECT id FROM users WHERE username='$username' AND password='$password'");
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            // Fetch favorite products
            $favorites_result = $conn->query("SELECT product_id FROM favorites WHERE user_id=" . $user['id']);
            $favorites = [];
            while ($row = $favorites_result->fetch_assoc()) {
                $favorites[] = $row['product_id'];
            }
        } else {
            echo "Invalid login credentials.";
        }
    }

    // Add favorite item
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_favorite'])) {
        $product_id = $conn->real_escape_string($_POST['product_id']);
        $user_id = $_SESSION['user_id'];

        // Check if the product is already a favorite
        $check_query = $conn->query("SELECT * FROM favorites WHERE user_id=$user_id AND product_id='$product_id'");
        
        if ($check_query->num_rows == 0) {
            $sql = "INSERT INTO favorites (user_id, product_id) VALUES ('$user_id', '$product_id')";
            if ($conn->query($sql) === TRUE) {
                echo "Product added to favorites!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "This product is already in your favorites.";
        }
    }

    // Fetch products from JSON file
    $products = json_decode(file_get_contents('products.json'), true);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Favorite Products</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }

            h1 {
                color: #333;
            }

            .product {
                border: 1px solid #ddd;
                padding: 10px;
                margin: 10px 0;
                display: flex;
                align-items: center;
            }

            .product img {
                max-width: 100px;
                margin-right: 10px;
            }

            button {
                background-color: #007BFF;
                color: white;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
            }

            button:hover {
                background-color: #0056b3;
            }

            form {
                margin-bottom: 20px;
            }

            ul {
                list-style-type: none;
                padding: 0;
            }
        </style>
    </head>
    <body>
        <h1>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></h1>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <h2>Select Your Favorite Products</h2>
            <div id="products">
                <?php foreach ($products as $product_id => $product): ?>
                    <div class="product">
                        <img src="<?php echo htmlspecialchars($product['img_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['summary']); ?></p>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                            <button type="submit" name="add_favorite">Add to Favorites</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2>Your Favorite Products</h2>
            <ul>
                <?php foreach ($favorites as $favorite_id): ?>
                    <?php if (isset($products[$favorite_id])): ?>
                        <li><?php echo htmlspecialchars($products[$favorite_id]['name']); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

        <?php else: ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </body>
    </html>
