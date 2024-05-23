<?php
session_start();
require 'includes/db_connection.php';

$user_id = $_SESSION['user_PID'];

$sql = "SELECT * FROM people WHERE PID='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "User not found";
    exit();
}

$user = $result->fetch_assoc();

function updateProfile($conn, $user_id, $email, $f_name, $l_name, $dob, $phone) {
    $sql = "SELECT email, f_name, l_name, DOB, phone FROM people WHERE PID='$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $current = $result->fetch_assoc();

        if (empty($email)) {
            $email = $current['email'];
        }
        if (empty($f_name)) {
            $f_name = $current['f_name'];
        }
        if (empty($l_name)) {
            $l_name = $current['l_name'];
        }
        if (empty($dob)) {
            $dob = $current['DOB'];
        }
        if (empty($phone)) {
            $phone = $current['phone'];
        }

        $sql = "UPDATE people SET email='$email', f_name='$f_name', l_name='$l_name', DOB='$dob', phone='$phone' WHERE PID='$user_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Profile updated successfully";
        } else {
            echo "Error updating profile: " . $conn->error;
        }
    }
}

function updateUsername($conn, $user_id, $new_username) {
    $sql = "UPDATE people SET username='$new_username' WHERE PID='$user_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Username updated successfully";
    } else {
        echo "Error updating username: " . $conn->error;
    }
}

function updatePassword($conn, $user_id, $old_password, $new_password) {
    $sql = "SELECT p_password FROM people WHERE PID='$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($old_password, $user['p_password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE people SET p_password='$hashed_password' WHERE PID='$user_id'";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Password updated successfully');</script>";
            } else {
                echo "<script>alert('Error updating password: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Incorrect old password');</script>";
        }
    }
}

function addAddress($conn, $user, $country, $city, $postal_code, $address) {
    $sql = "INSERT INTO addresses (user, country, city, postal_code, address) VALUES ('$user', '$country', '$city', '$postal_code', '$address')";
    if ($conn->query($sql) === TRUE) {
        echo "Address added successfully";
    } else {
        echo "Error adding address: " . $conn->error;
    }
}

function getAddresses($conn, $user) {
    $sql = "SELECT * FROM addresses WHERE user='$user'";
    $result = $conn->query($sql);
    $addresses = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $addresses[] = $row;
        }
    }
    return $addresses;
}

if (isset($_POST['update_profile'])) {
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    updateProfile($conn, $user_id, $email, $f_name, $l_name, $dob, $phone);
}

if (isset($_POST['update_username'])) {
    $user_id = $_POST['user_id'];
    $new_username = $_POST['new_username'];
    updateUsername($conn, $user_id, $new_username);
}

if (isset($_POST['update_password'])) {
    $user_id = $_POST['user_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    updatePassword($conn, $user_id, $old_password, $new_password);
}

if (isset($_POST['add_address'])) {
    $user = $_POST['user'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $address = $_POST['address'];
    addAddress($conn, $user, $country, $city, $postal_code, $address);
}

$addresses = getAddresses($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=4.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="includes/header.css">
    <link rel="stylesheet" href="user_profile.css">
</head>
<body>
<?php require 'includes/header.php'?>
<div class="search-box" id="search-box">
    <input id="search-input" type="text" placeholder="Search...">
    <div id="search-results"></div>
</div>
<div class="profile-container">
    <h1><?php echo $user['username']?></h1>
    <div class="profile-details">
        <h2>Personal Information</h2>
        <p><strong>First Name:</strong> <?php echo $user['f_name']; ?></p>
        <p><strong>Last Name:</strong> <?php echo $user['l_name']; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $user['DOB']; ?></p>

        <h2>Contact Information</h2>
        <p><strong>Phone Number:</strong> <?php echo $user['phone']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>

        <h2>Addresses</h2>
        <table>
            <tr>
                <th>Country</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Address</th>
            </tr>
            <?php foreach ($addresses as $address): ?>
                <tr>
                    <td><?php echo $address['country']; ?></td>
                    <td><?php echo $address['city']; ?></td>
                    <td><?php echo $address['postal_code']; ?></td>
                    <td><?php echo $address['address']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button class="btn" onclick="toggleUpdateForm()">Update Profile</button>

        <form id="update-form" method="post" action="" style="display:none;">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="email" name="email" placeholder="New Email">
            <input type="text" name="f_name" placeholder="New First Name">
            <input type="text" name="l_name" placeholder="New Last Name">
            <input type="date" name="dob" placeholder="New Date of Birth">
            <input type="tel" name="phone" placeholder="New Phone Number">
            <button class="btn" type="submit" name="update_profile">Update</button>
        </form>

        <button class="btn" onclick="toggleUsernameForm()">Change Username</button>

        <form id="username-form" method="post" action="" style="display:none;">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="text" name="new_username" placeholder="New Username">
            <button class="btn" type="submit" name="update_username">Change Username</button>
        </form>

        <button class="btn" onclick="togglePasswordForm()">Change Password</button>

        <form id="password-form" method="post" action="" style="display:none;">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="password" name="old_password" placeholder="Old Password">
            <input type="password" name="new_password" placeholder="New Password">
            <button class="btn" type="submit" name="update_password">Change Password</button>
        </form>

        <button class="btn" onclick="toggleAddressForm()">Add Address</button>

        <form id="address-form" method="post" action="" style="display:none;">
            <input type="hidden" name="user" value="<?php echo $user_id; ?>">
            <input type="text" name="country" placeholder="Country">
            <input type="text" name="city" placeholder="City">
            <input type="text" name="postal_code" placeholder="Postal Code">
            <input type="text" name="address" placeholder="Address">
            <button class="btn" type="submit" name="add_address">Add Address</button>
        </form>
    </div>
</div>
</body>
<script src="user_profile_script.js"></script>
</html>
