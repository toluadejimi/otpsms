<?php
include("../auth.php");

if (isset($_SESSION['admin']) == "") {
    echo 'error';
} else {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // SQL query to delete the product
        $sql = "DELETE FROM product_details WHERE id='$id'";

        if (mysqli_query($conn, $sql)) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}
