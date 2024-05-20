<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded.";

                $sql = "INSERT INTO products (p_name, description, regular_price, current_price, quantity, p_image, category, brand)
                        VALUES ('{$_POST['p_name']}', '{$_POST['description']}', '{$_POST['regular_price']}', 
                                '{$_POST['current_price']}', '{$_POST['quantity']}', '$target_file', '{$_POST['category']}', '{$_POST['brand']}')";

                if ($conn->query($sql) === TRUE) {
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "File is not an image.";
    }

    $conn->close();
}
$sql_categories = "SELECT CRID, c_name FROM categories";
$result_categories = $conn->query($sql_categories);
$categories = [];
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
}

$sql_brands = "SELECT BID, b_name FROM brands";
$result_brands = $conn->query($sql_brands);
$brands = [];
if ($result_brands->num_rows > 0) {
    while ($row = $result_brands->fetch_assoc()) {
        $brands[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Add New Product</h1>
<form action="" method="post" enctype="multipart/form-data">
    <label for="p_name">Product Name:</label>
    <input type="text" id="p_name" name="p_name" required><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description" rows="4" cols="50"></textarea><br>

    <label for="regular_price">Regular Price:</label>
    <input type="number" id="regular_price" name="regular_price" step="0.01" required><br>

    <label for="current_price">Current Price:</label>
    <input type="number" id="current_price" name="current_price" step="0.01" required><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br>

    <label for="category">Category:</label>
    <select id="category" name="category" required>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['CRID']; ?>"><?php echo htmlspecialchars($category['c_name']); ?></option>
        <?php endforeach; ?>
    </select><br>

    <label for="brand">Brand:</label>
    <select id="brand" name="brand" required>
        <?php foreach ($brands as $brand): ?>
            <option value="<?php echo $brand['BID']; ?>"><?php echo htmlspecialchars($brand['b_name']); ?></option>
        <?php endforeach; ?>
    </select><br>

    <label for="image">Select image:</label>
    <input type="file" id="image" name="image" accept="image/*" required><br>

    <button type="submit">Add Product</button>
</form>
</body>
</html>
