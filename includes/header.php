<header>
    <div class="name">
        <a href="home_page.php">JEWELRY PORODICA KARALIĆ</a>
    </div>
    <input type="checkbox" id="check">
    <label for="check" class="icons">
        <i class='bx bx-menu' id="menu"></i>
        <i class='bx bx-x' id="x"></i>
    </label>
    <?php
    require 'includes/db_connection.php';

    $sql = "SELECT CRID, c_name FROM categories";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<nav class="products-bar">';
        while ($row = $result->fetch_assoc()) {
            echo '<a href="/jewelry_shop/category.php?CRID=' . $row["CRID"] . '">' . $row["c_name"] . '</a>';
        }
        echo '</nav>';
    } else {
        echo "No categories found";
    }
    $conn->close();
    ?>
    <nav class="navbar">
        <div class="search-icon" title="Search"><i class='bx bx-search'></i></div>
        <?php if (!isset($_SESSION['user_type'])) : ?>
            <a href="login.php" title="Login"><i class='bx bx-user'></i></a>
        <?php else : ?>
            <a href="?user_profile" title="User Profile"><i class='bx bx-user'></i></a>
            <a href="?logout" title="Logout"><i class='bx bx-log-out'></i></a>
        <?php endif; ?>
        <a href="?wishlist" title="Wishlist"><i class='bx bx-heart'></i></a>
        <a href="?shopping_bag" title="Shopping Bag"><i class='bx bx-shopping-bag'></i></a>
    </nav>
</header>