<?php
global $conn;
require 'includes/header_sessions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'includes/db_connection.php';

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error_message'] = "All fields are required";
    } elseif ($password != $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match";
    } else {
        $check_query = "SELECT * FROM people WHERE username='$username' OR email='$email'";
        $result = $conn->query($check_query);

        if ($result->num_rows > 0) {
            $_SESSION['error_message'] = "Username or email already exists";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO people (username, email, p_password) VALUES ('$username', '$email', '$hashed_password')";
            if ($conn->query($insert_query) === TRUE) {
                $user_id = $conn->insert_id;
                $insert_address_query = "INSERT INTO addresses (people) VALUES ('$user_id')";
                if ($conn->query($insert_address_query) === TRUE) {
                    $_SESSION['success_message'] = "Registration successful";
                    header("Location: login.php");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Error: " . $conn->error;
                }

            } else {
                $_SESSION['error_message'] = "Error: " . $conn->error;
            }
        }
    }
    $conn->close();
}
if(isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=4.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="includes/header.css">
    <link rel="stylesheet" href="login-register.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="login-register">
<?php require 'includes/header.php'?>
<div class="search-box" id="search-box">
    <input id="search-input" type="text" placeholder="Search...">
    <div id="search-results"></div>
</div>

<div class="wrapper">
    <form action="" method="post">
        <h1>Register</h1>
        <div class="input_box">
            <label>
                <input type="text" name="username" placeholder="username" required>
            </label>
            <i class='bx bx-user'></i>
        </div>
        <div class="input_box">
            <label>
                <input type="text" name="email" placeholder="email" required>
            </label>
            <i class='bx bx-envelope'></i>
        </div>
        <div class="input_box">
            <label>
                <input type="password" name="password" placeholder="password" required>
            </label>
            <i class='bx bx-lock-alt' ></i>
        </div>
        <div class="input_box">
            <label>
                <input type="password" name="confirm_password" placeholder="confirm password" required>
            </label>
            <i class='bx bx-lock-alt' ></i>
        </div>
        <div class="error">
            <?php
            if(isset($error_message)) { echo $error_message; }
            ?>
        </div>
        <button type="submit" class="btn">Register</button>
        <div class="register_link">
            <p>Already have an account?
                <a href="login.php">Login</a></p>
        </div>
    </form>
</div>
</body>
<script src="includes/header.js"></script>
</html>