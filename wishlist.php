<?php
require 'includes/header_sessions.php';
require 'includes/db_connection.php';

if (!isset($_SESSION['user_PID'])) {
    header("Location: login.php");
    exit();
}
$personId = $_SESSION['user_PID'];
$currentDate = date('Y-m-d');

$sql = "SELECT w.WID, p.p_name, p.current_price, p.quantity, p.p_image, w.w_comment, w.date_added
        FROM wishlists w, products p
        WHERE p. pid = w.w_product AND w.people = $personId
        AND (w.valid_until IS NULL OR w.valid_until > '$currentDate')";
$result = $conn->query($sql);

$wishlistItems = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $wishlistItems[] = $row;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=4.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Karalic</title>
    <link rel="stylesheet" href="includes/header.css">
    <link rel="stylesheet" href="wishlist.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </head>

<body class="home">
<?php require 'includes/header.php'?>
<div class="search-box" id="search-box">
    <input id="search-input" type="text" placeholder="Search...">
    <div id="search-results"></div>
</div>
<div class="wishlist-container">
    <h2>My Wishlist</h2>
    <form id="update-wishlist-form">
        <div id="wishlist-box">
            <?php if (empty($wishlistItems)): ?>
                <p>No products in your wishlist.</p>
            <?php else: ?>
                <table>
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Stock status</th>
                        <th>Date Added</th>
                        <th>Comment</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($wishlistItems as $item): ?>
                        <tr class="wishlist-item" data-wid="<?php echo $item['WID']; ?>">
                            <td class="item-image"><img src="images/<?php echo $item['p_image']; ?>" alt="<?php echo $item['p_name']; ?>"></td>
                            <td><?php echo $item['p_name']; ?></td>
                            <td>$<?php echo number_format($item['current_price'], 2); ?></td>
                            <td><?php echo ($item['quantity'] > 0) ? 'Available' : 'Out of Stock'; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($item['date_added'])); ?></td>
                            <td>
                                <input type="text" name="comment" data-wid="<?php echo $item['WID']; ?>" value="<?php echo htmlspecialchars($item['w_comment']); ?>">
                            </td>
                            <td class="actions">
                                <button type="button" class="add-to-cart" onclick="addToCart(<?php echo $item['WID']; ?>)">Add to Cart</button>
                                <button type="button" class="remove" onclick="removeFromWishlist(<?php echo $item['WID']; ?>)">X</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="5"></td>
                        <td class="update">
                            <button type="submit" class="update-button">Update</button>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </form>
</div>

</body>
<script src="includes/header.js"></script>
<script src="wishlist.js"></script>
</html>