<?php
$conn = new mysqli('130.61.214.91', 'root', 'Password123-', 'jewelry_store',  3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}