<?php
global $conn;
require 'includes/header_sessions.php';
require 'includes/db_connection.php';

$p_name = $current_price = $description = $brand = $image = $quantity = "";
$result_reviews = null;
$avg_rating = 0.0;

function getProductDetails($product_id) {
    global $conn;
    $sql = "SELECT p.p_name, p.current_price, p.description, b.b_name AS brand, p.p_image, p.quantity,
               (SELECT AVG(rating) FROM reviews WHERE product = $product_id) as avg_rating 
               FROM products p
               JOIN brands b ON p.brand = b.BID
               WHERE p.pid = $product_id";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        return $row;
    }
    return null;
}

if (isset($_GET['wishlist'])) {
    if ($_GET['wishlist'] == 'added') {
        echo '<p class="wishlist-message">Product added to wishlist.</p>';
    } elseif ($_GET['wishlist'] == 'exists') {
        echo '<p class="wishlist-message">Product is already in your wishlist.</p>';
    }
}

if (isset($_GET['pid'])) {
    $product_id = $_GET['pid'];
    $product_details = getProductDetails($product_id);

    if ($product_details) {
        $p_name = $product_details['p_name'];
        $current_price = $product_details['current_price'];
        $description = $product_details['description'];
        $brand = $product_details['brand'];
        $image = $product_details['p_image'];
        $quantity = $product_details['quantity'];
        $avg_rating = $product_details['avg_rating'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_type']) && isset($_SESSION['user_PID'])) {
        if (isset($_POST['pid']) && isset($_POST['rating']) && isset($_POST['comment'])) {
            $product_id = $_POST['pid'];
            $rating = $_POST['rating'];
            $comment = $_POST['comment'];
            $people_id = $_SESSION['user_PID'];

            $sql = "INSERT INTO reviews (rating, comment, r_date, people, product) 
                    VALUES (?, ?, CURDATE(), ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isii", $rating, $comment, $people_id, $product_id);

            if ($stmt->execute()) {
                echo "Review submitted successfully!";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        header("Location: login.php");
        exit();
    }
}

$sql_reviews = "SELECT r.rating, r.comment, p.f_name AS reviewer_name 
                FROM reviews r 
                JOIN people p ON r.people = p.PID 
                WHERE r.product = $product_id AND r.comment IS NOT NULL AND r.comment != ''";
$result_reviews = $conn->query($sql_reviews);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $p_name; ?></title>
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="includes/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="includes/header.css">
</head>

<body>
<?php require 'includes/header.php'?>
<div class="search-box" id="search-box">
    <input id="search-input" type="text" placeholder="Search...">
    <div id="search-results"></div>
</div>

<div class="product-container">
    <div class="product-image">
        <img src="images/<?php echo $image; ?>" alt="Product Image" style="max-width: 100%; height: auto;">
    </div>

    <div class="product-details">
        <h1><?php echo $p_name; ?></h1>
        <div class="product-details">
            <p>Price: <?php echo $current_price; ?> BAM</p>
            <p>Brand: <?php echo $brand; ?></p>
            <p>Quantity: <?php echo $quantity; ?></p>
        </div>
        <div class="product-description">
            <h2>Description</h2>
            <p><?php echo $description; ?></p>
        </div>

        <div class="buttons">
            <form action="add_to_wishlist.php" method="post">
                <input type="hidden" name="pid" value="<?php echo $product_id; ?>">
                <button type="submit" title="Wishlist"><i class='bx bx-heart icon'></i></button>
            </form>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="pid" value="<?php echo $product_id; ?>">
                <button type="submit" title="Add to Cart"><i class='bx bx-shopping-bag icon'></i></button>
            </form>
        </div>
    </div>
</div>

<section id="reviews">
    <h2>Reviews</h2>
    <p>Average Rating:
        <?php
        for ($i = 0; $i < floor($avg_rating); $i++) {
            echo '<i class="fa fa-star" style="color: gold;"></i>';
        }

        if ($avg_rating - floor($avg_rating) >= 0.0) {
            echo '<i class="fa fa-star-half-o" style="color: gold;"></i>';
        }

        for ($i = ceil($avg_rating); $i < 5; $i++) {
            echo '<i class="fa fa-star-o" style="color: gold;"></i>';
        }
        ?>
        (<?php echo round($avg_rating, 1); ?> / 5)
    </p>
    <ul>
        <?php
        if ($result_reviews && $result_reviews->num_rows > 0) {
            while ($row = $result_reviews->fetch_assoc()) {
                ?>
                <li class="review-item">
                    <p class="reviewer-name">- <?php echo $row['reviewer_name']; ?></p>
                    <span><?php echo $row['comment']; ?></span>
                </li>
                <?php
            }
        } else {
            echo "<li>No reviews available.</li>";
        }
        ?>
    </ul>

    <form class="review-form" action="" method="post">
        <input type="hidden" name="pid" value="<?php echo $product_id; ?>">
        <label for="rating">Rating (1-5):</label>
        <input type="number" id="rating" name="rating" min="1" max="5" required>
        <label for="comment">Your Review:</label>
        <textarea id="comment" name="comment"></textarea>
        <button type="submit">Submit Review</button>
    </form>
</section>
</body>
<?php require 'includes/footer.php'?>
</html>
