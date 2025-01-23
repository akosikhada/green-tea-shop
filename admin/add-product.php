<?php
    include "../components/connection.php";
    
    session_start();

    $admin_id = $_SESSION["admin_id"];

    if (!isset($admin_id)) {
        header("Location: login.php");
    }

    // ADD PRODUCT IN DATABASE

    if (isset($_POST["publish"])) {
        $id = unique_id();

        $name = $_POST["name"];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $price = $_POST["price"];
        $price = filter_var($price, FILTER_SANITIZE_STRING);

        $content = $_POST["content"];
        $content = filter_var($content, FILTER_SANITIZE_STRING);

        $status = "active";

        $image = $_FILES["image"]["name"];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES["image"]["size"];
        $image_tmp_name = $_FILES["image"]["tmp_name"];
        $image_folder = "../image/" .$image;

        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ?");
        $select_image->execute([$image]);

        if (isset($image)) {
            if ($select_image->rowCount() > 0) {
                $warning_msg[] = "Image name already exists";
            } elseif ($image_size > 9999999999) {
                $warning_msg[] = "Image size is too large";
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
            }
        } else {
            $image = "";
        }
        if ($select_image->rowCount() > 0 AND $image != "") {
            $warning_msg[] = "Please rename the image";
        } else {
            $insert_product = $conn->prepare("INSERT INTO `products` (id, name, price, image, product_detail, status) VALUES( ?, ?, ?, ?, ?, ?)");
            $insert_product->execute([$id, $name, $price, $image, $content, $status]);
            $success_msg[] = "Product added successfully!";
        }
    }

    // SAVE AS DRAFT PRODUCT IN DATABASE

    if (isset($_POST["draft"])) {
        $id = unique_id();

        $name = $_POST["name"];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $price = $_POST["price"];
        $price = filter_var($price, FILTER_SANITIZE_STRING);

        $content = $_POST["content"];
        $content = filter_var($content, FILTER_SANITIZE_STRING);

        $status = "inactive";

        $image = $_FILES["image"]["name"];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES["image"]["size"];
        $image_tmp_name = $_FILES["image"]["tmp_name"];
        $image_folder = "../image/" .$image;

        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ?");
        $select_image->execute([$image]);

        if (isset($image)) {
            if ($select_image->rowCount() > 0) {
                $warning_msg[] = "Image name already exists";
            } elseif ($image_size > 9999999999) {
                $warning_msg[] = "Image size is too large";
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
            }
        } else {
            $image = "";
        }
        if ($select_image->rowCount() > 0 AND $image != "") {
            $warning_msg[] = "Image name already exists";
        } else {
            $insert_product = $conn->prepare("INSERT INTO `products` (id, name, price, image, product_detail, status) VALUES( ?, ?, ?, ?, ?, ?)");
            $insert_product->execute([$id, $name, $price, $image, $content, $status]);
            $success_msg[] = "Product Saved successfully";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BOXICON CDN LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css" integrity="sha512-cn16Qw8mzTBKpu08X0fwhTSv02kK/FojjNLz0bwp2xJ4H+yalwzXKFw/5cLzuBZCxGWIA+95X4skzvo8STNtSg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM CSS LINK -->
    <link rel="stylesheet" type="text/css" href="admin-style.css" v=<?php echo time(); ?>>
    <title>Green Tea Admin Panel - Add Product Page</title>
</head>
<body>
    <?php include "../components/admin-header.php"; ?>
    <div class="main">
        <div class="banner">
            <h1>Add Product</h1>
        </div>
        <div class="title-2">
            <a href="dashboard.php">Dashboard</a><span> / Add Product</span>
        </div>
        <section class="form-container">
            <h1 class="heading">Add Product</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="input-field">
                    <label>Product Name <sup>*</sup></label>
                    <input type="text" name="name" maxlength="99999" required placeholder="Enter product name">
                </div>
                <div class="input-field">
                    <label>Product Price <sup>*</sup></label>
                    <input type="number" name="price" maxlength="99999" required placeholder="Enter product price">
                </div>
                <div class="input-field">
                    <label>Product Detail <sup>*</sup></label>
                    <textarea name="content" required maxlength="99999" required placeholder="Enter product detail"></textarea>
                </div>
                <div class="input-field">
                    <label>Product Image <sup>*</sup></label>
                    <input type="file" name="image" maxlength="99999" accept="image/*" required>
                </div>
                <div class="flex-btn">
                    <button type="submit" name="publish" class="btn">Publish Product</button>
                    <button type="submit" name="draft" class="btn">Save As Draft Product</button>
                </div>
            </form>
        </section>
    </div>
    <!-- SWEETALERT CDN LINK -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- CUSTOM JAVASCRIPT LINK -->
    <script type="text/javascript" src="admin.js"></script>
    <!-- ALERT -->
    <?php include "../components/alert.php"; ?>
</body>
</html>