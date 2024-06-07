<?php
global $conn;
session_start();

require '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE people SET username='{$_POST['username']}', email='{$_POST['email']}', f_name='{$_POST['f_name']}', 
                    l_name='{$_POST['l_name']}', DOB='{$_POST['DOB']}', phone='{$_POST['phone']}' WHERE PID='{$_POST['PID']}'";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating user: " . $conn->error;
    }
}

$user = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM people WHERE PID='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Edit Brand</h1>
<form action="" method="post">
    <label for="PID">User ID:</label>
    <input type="text" id="PID" name="PID" value="<?php echo $user['PID'] ?>" readonly><br>

    <label for="f_name">New First Name:</label>
    <input type="text" id="f_name" name="f_name" value="<?php echo $user['f_name'] ?>"><br>

    <label for="l_name">New Last Name:</label>
    <input type="text" id="l_name" name="l_name" value="<?php echo $user['l_name'] ?>"><br>

    <label for="username">New Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $user['username'] ?>"><br>

    <label for="pass">New Password:</label>
    <input type="text" id="pass" name="pass" value="<?php echo $user['p_password'] ?>"><br>

    <label for="dob">New Date of Birth:</label>
    <input type="text" id="dob" name="dob" placeholder="YYYY-MM-DD" value="<?php echo $user['DOB'] ?>"><br>

    <label for="phone">New Phone:</label>
    <input type="text" id="phone" name="phone" value="<?php echo $user['phone'] ?>"><br>

    <label for="email">New Email:</label>
    <input type="text" id="email" name="email" value="<?php echo $user['email'] ?>"><br>

    <button type="submit">Edit User</button>
</form>
</body>
</html>
