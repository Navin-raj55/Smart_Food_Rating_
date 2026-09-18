<?php 
session_start();
include 'db_connection.php'; // Make sure to include your database connection file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_favorite'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session

    // Prepare and execute the SQL query to insert favorite product
    $stmt = $conn->prepare("INSERT INTO favorite (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id); // Bind parameters (user_id and product_id)
    
    if ($stmt->execute()) {
        echo "<script>alert('Product added to favorites!');</script>";
    } else {
        echo "<script>alert('Error adding product to favorites.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Responsive UI - Sting Energy</title>
    
    <!-- Font Awesome CDN -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
     body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: #e0f7fa; /* Light blue background */
        transition: background-color 0.5s ease; /* Smooth transition for background color */
    }

    .name-color {
        color: #1e88e5; /* Darker blue for the name */
    }

    .container {
        padding: 20px;
        padding-bottom: 80px; /* Increased padding at the bottom to prevent overlap */
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        animation: fadeIn 1s; /* Fade in animation for the header */
    }

    .header h1 {
        font-size: 24px;
        color: #0d47a1; /* Dark blue */
        display: flex;
        align-items: center; /* Vertically center the icon and text */
        transition: color 0.3s ease; /* Transition for text color */
    }

    .header h1:hover {
        color: #1976d2; /* Change color on hover */
    }

    .header h1 img {
        width: 40px; /* Set your icon size */
        height: 40px;
        margin-right: 10px; /* Space between icon and text */
        border-radius: 50%; /* Circular icon, optional */
    }

    .user-actions {
        display: flex;
        align-items: center; /* Align button and icon vertically */
    }

    .search-bar {
        margin: 20px 0;
    }

    .search-bar input {
        width: 95%;
        padding: 10px;
        border-radius: 20px;
        border: 1px solid #1e88e5; /* Blue border */
        font-size: 16px;
        transition: border-color 0.3s; /* Smooth border transition */
    }

    .search-bar input:focus {
        border-color: #0d47a1; /* Darker blue on focus */
        outline: none; /* Remove outline */
    }

    .product-card {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
        position: relative;
        transition: transform 0.3s; /* Add transition for hover effect */
    }

    .product-card:hover {
        transform: scale(1.02); /* Scale up effect on hover */
    }

    .product-card img {
        width: 150px; /* Set a fixed width for the image */
        height: auto; /* Maintain aspect ratio */
        border-radius: 10px; /* Rounded corners */
        margin-top: 10px; /* Space between text and image */
        float: left; /* Float the image to the left */
        margin-right: 15px; /* Space between the image and text */
        transition: transform 0.3s; /* Image hover effect */
    }

    .product-card img:hover {
        transform: scale(1.05); /* Slightly enlarge the image on hover */
    }

    .product-card h2 {
        color: #1976d2; /* Blue for the product title */
        font-size: 22px;
        margin-bottom: 10px;
    }

    .product-card p {
        font-size: 14px;
        line-height: 1.5;
    }

    .rating {
        margin-top: 10px;
    }

    .rating span {
        font-size: 14px;
        color: green;
        font-weight: bold;
    }

    .best-choice-image {
        width: 100%; /* Ensures it takes the full width of its container */
        height: auto; /* Keeps the image's aspect ratio */
        border-radius: 10px; /* Matches the rounded corners */
        margin-bottom: 20px;
    }

    .scan-footer-container {
        position: fixed;
        bottom: 0;
        width: 100%;
        margin-left: -20px;
        text-align: center;
        padding: 10px;
        color: white;
        font-size: 18px;
        cursor: pointer;
        background-color: #0d47a1; /* Dark blue background for the footer */
        transition: background-color 0.3s; /* Transition for background color */
    }

    .scan-footer-container:hover {
        background-color: #1976d2; /* Lighter blue on hover */
    }

    .scan-footer-container .slide-track {
        position: relative;
        background-color: #fff;
        border-radius: 30px;
        height: 60px; /* Adjusted height */
        width: 80%;
        margin: 0 auto;
        overflow: hidden;
        display: flex;
        align-items: center;
    }

    .scan-footer-container .slide-button {
        position: absolute;
        left: 0;
        width: 60px;
        height: 60px; /* Made circular */
        background-color: #1e88e5; /* Blue background */
        border-radius: 50%; /* Circular shape */
        display: flex;
        justify-content: center;
        align-items: center;
        color: white; /* White text */
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        transition: left 0.2s ease, background-color 0.3s; /* Transition for button position and background color */
    }

    .scan-footer-container .slide-button:hover {
        background-color: #0d47a1; /* Darker blue on hover */
    }

    .scan-footer-container .slide-button img {
        width: 30px; /* Adjust size of the scanner icon */
        height: 30px; /* Adjust size of the scanner icon */
    }

    .scan-footer-container .scan-message {
        width: 100%;
        text-align: center;
        position: absolute;
        z-index: 1;
        color: black;
        font-size: 30px;
        user-select: none;
    }

    .sign-out-button {
        background-color: #1e88e5; /* Blue background */
        color: white; /* White text */
        border: none; /* Remove default border */
        padding: 10px 15px; /* Padding for button */
        border-radius: 5px; /* Rounded corners */
        cursor: pointer; /* Pointer cursor on hover */
        font-size: 16px; /* Font size */
        margin: 15px;
        margin-left: 10px; /* Space between the button and the menu icon */
        transition: background-color 0.3s; /* Transition for button color */
    }

    .sign-out-button:hover {
        background-color: #0d47a1; /* Darker blue on hover */
    }

    .menu-icon {
        width: 30px; /* Adjust the size as needed */
        height: 30px; /* Adjust the size as needed */
        cursor: pointer; /* Pointer cursor on hover */
        transition: transform 0.3s; /* Transition for icon */
    }

    .menu-icon:hover {
        transform: scale(1.1); /* Scale up effect on hover */
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .product-card img {
            width: 100%; /* Adjusted for smaller screens */
            float: none; /* Remove float on smaller screens */
            display: block; /* Display block for better layout */
            margin: 0 auto; /* Center image */
        }
        .header h1 {
            font-size: 20px;
        }
    }

    /* Keyframes for fade-in animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Section -->
    <div class="header">
        <h1>
            <img src="./assert/user-profile.png" alt="User Icon"> 
            Hello, <span class="name-color"><?php echo $_SESSION['name']?></span>
        </h1>
        <div class="user-actions">
            <button class="sign-out-button" onclick="redirectToCreate()">Sign Out</button>
            <img src="./assert/menu.png" alt="Menu Icon" class="menu-icon" onclick="redirectToProfile()">
        </div>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <input type="text" placeholder="Search for Product" id="search-input" />
    </div>

    <!-- Product Card Section -->
    <div class="product-card" id="product-card">
        <h2 id="product-name">Sting</h2>
        <img src="./assert/sting.jpg" alt="Sting Energy Drink" id="product-image" class="product-image">
        <p id="product-summary">Sting Energy Drink is a carbonated beverage designed to provide an instant energy boost through caffeine, taurine, and other stimulants.</p>
        <ul id="product-ingredients">
            <li>Carbonated Water</li>
            <li>Glucose</li>
            <li>Sucrose</li>
            <li>Citric Acid</li>
            <li>Taurine</li>
            <li>Sodium Citrate</li>
            <li>Caffeine</li>
            <li>Artificial Flavor</li>
            <li>Sodium Benzoate</li>
            <li>Pantothenic Acid (Vitamin B5)</li>
            <li>Pyridoxine Hydrochloride (Vitamin B6)</li>
            <li>Riboflavin (Vitamin B2)</li>
        </ul>
        <div class="rating" id="product-rating">
            <span>Rating: ★★★★★ 4.5</span>
        </div>
        <form method="POST" id="favorite-form" style="display: inline-block; margin-top: 10px;">
            <input type="hidden" name="product_id" id="favorite-product-id" value="">
            <button type="submit" name="add_favorite" class="add-favorite-button">Add to Favorites</button>
        </form>
    </div>

    <!-- People's Best Choice Section -->
    <div class="people-choice">
        <img src="./assert/multilogo.png" alt="Best Choice" class="best-choice-image">
    </div>

    <!-- Slide to Scan Footer -->
    <div class="scan-footer-container">
        <div class="slide-track">
            <div class="scan-message">Slide to Scan</div>
            <div class="slide-button">
                <img src="./assert/qr-code-scan.png" alt="Scanner Icon">
            </div>
        </div>
    </div>
</div>

<script>
    // Redirect function for Sign Out
    function redirectToCreate() {
        window.location.href = './logout.php'; // Adjust this path to your sign-out script
    }

    // Redirect function for Profile Menu Icon
    function redirectToProfile() {
        window.location.href = './profile.php'; // Adjust this path to your profile page
    }

    const slideButton = document.querySelector('.slide-button');
    const slideTrack = document.querySelector('.slide-track');
    const maxSlide = slideTrack.offsetWidth - slideButton.offsetWidth;
    let isDragging = false;
    let startX = 0;

    // Function to handle dragging on both mouse and touch devices
    function startDrag(e) {
        isDragging = true;
        startX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
    }

    function moveDrag(e) {
        if (isDragging) {
            const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            let left = clientX - startX + slideButton.offsetLeft;
            if (left < 0) left = 0;
            if (left > maxSlide) left = maxSlide;
            slideButton.style.left = left + 'px';
        }
    }

    function endDrag() {
        if (isDragging) {
            const left = parseInt(slideButton.style.left);
            if (left >= maxSlide) {
                // Redirect to another page when sliding is successful
                window.location.href = 'qr.html'; // Replace with your target page
            }
            // Reset button position after sliding
            slideButton.style.left = '0px';
            isDragging = false;
        }
    }

    // Temporary click event
    slideButton.addEventListener('click', function() {
        // Redirect on click
        window.location.href = 'qr.html'; // Replace with your target page
    });

    // Add event listeners for both mouse and touch events
    slideButton.addEventListener('mousedown', startDrag);
    slideButton.addEventListener('touchstart', startDrag);

    document.addEventListener('mousemove', moveDrag);
    document.addEventListener('touchmove', moveDrag);

    document.addEventListener('mouseup', endDrag);
    document.addEventListener('touchend', endDrag);

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const productCard = document.getElementById('product-card');
        const productName = document.getElementById('product-name');
        const productImage = document.getElementById('product-image');
        const productSummary = document.getElementById('product-summary');
        const productIngredients = document.getElementById('product-ingredients');
        const productRating = document.getElementById('product-rating');
        const favoriteProductId = document.getElementById('favorite-product-id');

        // Function to set default product details
        function setDefaultProduct() {
            productName.textContent = 'Sting';
            productImage.src = './assert/sting.jpg';
            productSummary.textContent = 'Sting Energy Drink is a carbonated beverage designed to provide an instant energy boost through caffeine, taurine, and other stimulants.';
            productIngredients.innerHTML = `
                <li>Carbonated Water</li>
                <li>Glucose</li>
                <li>Sucrose</li>
                <li>Citric Acid</li>
                <li>Taurine</li>
                <li>Sodium Citrate</li>
                <li>Caffeine</li>
                <li>Artificial Flavor</li>
                <li>Sodium Benzoate</li>
                <li>Pantothenic Acid (Vitamin B5)</li>
                <li>Pyridoxine Hydrochloride (Vitamin B6)</li>
                <li>Riboflavin (Vitamin B2)</li>
            `;
            productRating.innerHTML = `<span>Rating: ★★★★★ 4.5</span>`;
            favoriteProductId.value = ''; // Reset favorite product ID
        }

        // Set default product on initial load
        setDefaultProduct();

        searchInput.addEventListener('input', function() {
            const query = searchInput.value.toLowerCase();
            // Logic to update product card based on search query
            // For demonstration, it just resets to default
            if (query === 'sting') {
                setDefaultProduct();
                favoriteProductId.value = '1'; // Example product ID
            } else {
                // Reset to default if no match
                setDefaultProduct();
            }
        });
    });
</script>

</body>
</html>
