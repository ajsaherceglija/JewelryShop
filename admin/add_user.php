<?php
global $conn;
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require '../includes/db_connection.php';

    $sql = "INSERT INTO people (username, email, f_name, l_name, DOB, phone)
            VALUES ('{$_POST['username']}', '{$_POST['email']}', 
                    '{$_POST['f_name']}', '{$_POST['l_name']}', '{$_POST['DOB']}', '{$_POST['phone']}')";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_page.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Add New User</h1>
<form action="" method="post">
    <label for="f_name">First Name:</label>
    <input type="text" id="f_name" name="f_name" required><br>

    <label for="l_name">Last Name:</label>
    <input type="text" id="l_name" name="l_name" required><br>

    <label for="dob">Date of Birth:</label>
    <input type="text" id="dob" name="dob"><br>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="pass">Password:</label>
    <input type="text" id="pass" name="pass" required><br>

    <label for="email">Email:</label>
    <input type="text" id="email" name="email"><br>

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone"><br>

    <label for="type">User Type:</label>
    <select id="type" name="type" required>
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select><br>

    <button type="submit">Add User</button>
</form>
</body>
</html>
