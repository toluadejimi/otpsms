<?php
include("../auth.php");

if (isset($_SESSION['admin']) == "") {
    echo 'error';
} else {
    if (isset($_POST['id'], $_POST['details'])) {
        $id = $_POST['id'];
        $description = mysqli_real_escape_string($conn, $_POST['details']);

        $sql = "UPDATE product_details SET details='$description' WHERE id='$id'";

        if (mysqli_query($conn, $sql)) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}
