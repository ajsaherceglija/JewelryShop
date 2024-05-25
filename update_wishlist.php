<?php
require 'includes/db_connection.php';
session_start();

if (!isset($_SESSION['user_PID'])) {
    echo 'Error: User not logged in.';
    exit();
}

$personId = $_SESSION['user_PID'];
$currentDate = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['comments'])) {
        $comments = json_decode($_POST['comments'], true);

        foreach ($comments as $wid => $comment) {
            $wid = intval($wid);
            $comment = $conn->real_escape_string($comment);

            $sql = "UPDATE wishlists SET w_comment = '$comment' WHERE WID = '$wid' AND people = '$personId'";
            if ($conn->query($sql) !== TRUE) {
                echo 'Error: ' . $conn->error;
                exit();
            }
        }
        echo 'Success';
    }

    if (isset($_POST['remove_wid'])) {
        $wid = intval($_POST['remove_wid']);
        $wid = $conn->real_escape_string($wid);
        $personId = $conn->real_escape_string($personId);

        $sql = "UPDATE wishlists SET valid_until = '$currentDate' WHERE WID = '$wid' AND people = '$personId'";
        if ($conn->query($sql) !== TRUE) {
            echo 'Error: ' . $conn->error;
            exit();
        }
        echo 'Success';
    }
} else {
    echo 'Invalid request.';
}
?>
