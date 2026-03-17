<?php
include("../auth.php");

if (!isset($_SESSION['admin'])) {
    echo "Unauthorized access!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $stt = trim($_POST['stt']);
    $price = trim($_POST['price']);
    $category_id = trim($_POST['category_id']);
    $description = trim($_POST['description']);

    if (empty($name) || empty($price) || empty($description) || empty($category_id)) {
        echo "All fields are required!";
        exit;
    }

    if (!is_numeric($price)) {
        echo "Price must be a number.";
        exit;
    }

    // Insert product into the 'products' table
    $stmt = mysqli_prepare($conn, "INSERT INTO products (stt, name, price, description, category_id) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssdsi", $stt, $name, $price, $description, $category_id);

    if (!mysqli_stmt_execute($stmt)) {
        echo "Error inserting product: " . mysqli_error($conn);
        exit;
    }

    $product_id = mysqli_insert_id($conn);

    // Process uploaded accounts file
    if (isset($_FILES['accounts']) && $_FILES['accounts']['error'] == 0) {
        $fileTmpName = $_FILES['accounts']['tmp_name'];
        $fileContents = file_get_contents($fileTmpName);

        if ($fileContents === false) {
            echo "Error reading the file!";
            exit;
        }

        $lines = preg_split("/\r\n|\n|\r/", $fileContents);
        $lines = array_filter(array_map('trim', $lines));

        $stmt_detail = mysqli_prepare($conn, "INSERT INTO product_details (product_id, details, is_sold) VALUES (?, ?, 0)");

        foreach ($lines as $line) {
            if (!empty($line)) {
                mysqli_stmt_bind_param($stmt_detail, "is", $product_id, $line);
                if (!mysqli_stmt_execute($stmt_detail)) {
                    echo "Error inserting detail: " . mysqli_error($conn);
                    exit;
                }
            }
        }
    }

    echo 'success';
} else {
    echo 'Invalid request method.';
}
?>
