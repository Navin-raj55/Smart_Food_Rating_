<?php
session_start();
include 'db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user_id and product_id from the POST request
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];

    // Prepare and execute the SQL statement to insert into the favorites table
    $stmt = $conn->prepare("INSERT INTO favorite (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id); // Assuming both user_id and product_id are integers

    if ($stmt->execute()) {
        echo "Product added to favorites successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
