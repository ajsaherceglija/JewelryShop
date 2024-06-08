<?php
global $conn;
require 'includes/header_sessions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    require 'includes/db_connection.php';

    $username_email = $_POST['username_email'];
    $password = $_POST['password'];

    $sql = "SELECT PID, p_type, p_password FROM people WHERE username = '$username_email' OR email = '$username_email'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_type = $row['p_type'];
        $PID = $row['PID'];
        $hashed_password = $row['p_password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_type'] = $user_type;
            $_SESSION['user_PID'] = $PID;

            if ($user_type == 'admin') {
                header("Location: admin/admin_page.php");
                exit();
            } else {
                header("Location: home_page.php");
                exit();
            }
        }else {
            session_unset();
            session_destroy();
            $error_message = "Incorrect username/email or password.";
        }
    } else {
        session_unset();
        session_destroy();
        $error_message = "Incorrect username/email or password.";
    }

    $conn->close();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=4.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="login-register.css">
    <link rel="stylesheet" href="includes/header.css">
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
        <h1>Login</h1>
        <div class="input_box">
            <label>
                <input type="text" name="username_email" placeholder="username or email" required>
            </label>
            <i class='bx bx-user'></i>
        </div>
        <div class="input_box">
            <label>
                <input type="password" name="password" placeholder="password" required>
            </label>
            <i class='bx bx-lock-alt' ></i>
        </div>
        <div class="error">
            <?php
            if(isset($error_message)) { echo $error_message; }
            ?>
        </div>
        <button type="submit" class="btn">Login</button>
        <div class="register_link">
            <p>Don't have an account?
                <a href="register.php">Register</a></p>

        </div>
    </form>
</div>
</body>
<script src="includes/header.js"></script>
</html>
