<?php
include("../auth.php");

if (!isset($_SESSION['admin'])) {
    echo "Unauthorized access!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $stt = $_POST['stt'];
    $name = $_POST['name'];
    $category_id = trim($_POST['category_id']);
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    if (isset($_FILES['accounts']) && $_FILES['accounts']['error'] == 0) {
        $fileTmpName = $_FILES['accounts']['tmp_name'];

        $fileContents = file_get_contents($fileTmpName);

        if ($fileContents === false) {
            echo "Error reading the file!";
            exit;
        }

        $lines = explode("\n", $fileContents);
        $lines = array_filter($lines);

        // Insert each line into the product_details table
        foreach ($lines as $line) {
            $line = trim($line); // Remove any surrounding whitespace
            
            if (!empty($line)) {
                // Prepare the insert query for the product_details table
                $insertQuery = "INSERT INTO product_details (product_id, details, is_sold) VALUES ('$id', '$line', 0)";
                
                if (!mysqli_query($conn, $insertQuery)) {
                    echo 'Error inserting detail: ' . mysqli_error($conn);
                    exit;
                }
            }
        }
    }

    // Validate required fields
    if (empty($id) || empty($name) || empty($price) || empty($description) || empty($category_id)) {
        echo "All fields are required!";
        exit;
    }
    
    $product_query = mysqli_query($conn, "SELECT api_id FROM products WHERE id='$id'");
    if (mysqli_num_rows($product_query) == 0) {
        echo "Product not found!";
        exit;
    }
    $product = mysqli_fetch_assoc($product_query);

    // Update the product in the products table
    $sql = "UPDATE products SET stt = '$stt', name='$name', price='$price', description='$description', category_id='$category_id'";
    
    // Add update_price_from_api only if product is from API
    if ($product['api_id'] != 0 && isset($_POST['update_price_from_api'])) {
        $update_price_from_api = ($_POST['update_price_from_api'] == 1) ? 1 : 0;
        $sql .= ", update_price_from_api = '$update_price_from_api'";
    }
    
    $sql .= " WHERE id='$id'";
    
    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'Database update failed: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request method.';
}
?>
