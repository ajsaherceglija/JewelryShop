<?php require 'includes/header_sessions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=4.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Karalic</title>
    <link rel="stylesheet" href="includes/header.css">
    <link rel="stylesheet" href="home-page.css">
    <link rel="stylesheet" href="includes/footer.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />
</head>

<body class="home">
<?php require 'includes/header.php'?>
<div class="search-box" id="search-box">
    <input id="search-input" type="text" placeholder="Search...">
    <div id="search-results"></div>
</div>

<div class="header-image">
    <h1>PORODICA KARALIĆ</h1>
</div>
<h2 class="popular-picks">Most popular picks</h2>
<div class="container">
    <div class="slider">
        <button id="prev-slide" class="slide-button material-symbols-rounded">
            chevron_left
        </button>
        <ul class="image-list">

            <?php
            global $conn;
            require 'includes/db_connection.php';

            $sql = "SELECT pid, p_image FROM products LIMIT 7";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    echo '<a href="product.php?pid=' . $row["pid"] . '"><img class="image-item" src="images/'. $row["p_image"] . '" alt="Product Image" /></a>';
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>

        </ul>
        <button id="next-slide" class="slide-button material-symbols-rounded">
            chevron_right
        </button>
    </div>
    <div class="slider-scrollbar">
        <div class="scrollbar-track">
            <div class="scrollbar-thumb"></div>
        </div>
    </div>
</div>
</body>
<?php require 'includes/footer.php'?>
<script src="includes/header.js"></script>
<script src="slider.js" defer></script>
</html>