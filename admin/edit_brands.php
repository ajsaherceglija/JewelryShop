<?php
global $conn;
session_start();

require '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['BID'];
    $newName = $_POST['b_name'];
    $newEmail = $_POST['email'];
    $newPhone = $_POST['phone'];

    $sql = "UPDATE brands SET b_name = '$newName', email = '$newEmail', phone = '$newPhone' WHERE BID = '$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_page.php");
        exit();
    } else {
        echo "Error updating brand: " . $conn->error;
    }
}
$brand = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM brands WHERE BID='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $brand = $result->fetch_assoc();
    } else {
        echo "Brand not found.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Brand</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Edit Brand</h1>
<form action="" method="post">
    <label for="BID">Brand ID:</label>
    <input type="text" id="BID" name="BID" value="<?php echo $brand['BID'] ?>" readonly><br>

    <label for="b_name">New Name:</label>
    <input type="text" id="b_name" name="b_name" value="<?php echo $brand['b_name'] ?>"><br>

    <label for="email">New Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $brand['email'] ?>"><br>

    <label for="phone">New Phone:</label>
    <input type="tel" id="phone" name="phone" value="<?php echo $brand['phone'] ?>"><br>

    <button type="submit">Edit Brand</button>
</form>
</body>
</html>
