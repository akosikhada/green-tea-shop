<?php
    include "../components/connection.php";
    
    session_start();

    $admin_id = $_SESSION["admin_id"];

    if (!isset($admin_id)) {
        header("Location: login.php");
    }

    // UPDATE PRODUCT IN DATABASE
    if (isset($_POST["update"])) {
        $post_id = $_GET["id"];

        $name = $_POST["name"];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $price = $_POST["price"];
        $price = filter_var($price, FILTER_SANITIZE_STRING);

        $content = $_POST["content"];
        $content = filter_var($content, FILTER_SANITIZE_STRING);

        $status = $_POST["status"];
        $status = filter_var($status, FILTER_SANITIZE_STRING);

        // UPDATE PRODUCT 
        $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, product_detail = ?, status = ? WHERE id = ?");
        $update_product->execute([$name, $price, $content, $status, $post_id]);

        $success_msg[] = "Product updated successfully!";

        $old_image = $_POST["old_image"];
        $image = $_FILES["image"]["name"];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES["image"]["size"];
        $image_tmp_name = $_FILES["image"]["tmp_name"];
        $image_folder = "../image/" .$image;

        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ?");
        $select_image->execute([$image]);

        if (!empty($image)) {
            if ($image_size > 9999999999) {
                $warning_msg[] = "Image size is too large";
            } elseif ($select_image->rowCount()> 0 AND $image != ""){
                $warning_msg[] = "Image already exists";
            } else {
                $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
                $update_image->execute([$image, $post_id]);
                move_uploaded_file($image_tmp_name, $image_folder);

                if($old_image != $image AND $old_image != "") {
                    unlink("../image/".$old_image);
                }
                $success_msg[] = "Image updated successfully!";
            }
        }
    }

    // DELETE PRODUCT IN DATABASE
    if (isset($_POST["delete"])) {
        $p_id = $_POST["product_id"];
        $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);

       $delete_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
       $delete_image->execute(["$p_id"]);

       $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

       if ($fetch_delete_image["image"] != "") {
            unlink("../image/".$fetch_delete_image["image"]);
       }

        $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
        $delete_product->execute([$p_id]);
        header("Location: view-product.php");
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
    <title>Green Tea Admin Panel - Edit Product Page</title>
</head>
<body>
    <?php include "../components/admin-header.php"; ?>
    <div class="main">
        <div class="banner">
            <h1>Edit Product</h1>
        </div>
        <div class="title-2">
            <a href="dashboard.php">Dashboard</a><span> / Edit Product</span>
        </div>
        <section class="edit-post">
            <h1 class="heading">Edit Product</h1>
            <?php 
                $post_id = $_GET["id"];
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                $select_products->execute([$post_id]);

                if ($select_products->rowCount() > 0) {
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="form-container">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="old_image" value="<?php echo $fetch_products["image"]; ?>">
                    <input type="hidden" name="product_id" value="<?php echo $fetch_products["id"]; ?>">
                    <div class="input-field">
                        <label>Update Status</label>
                        <select name="status">
                            <option selected disabled value="<?php echo $fetch_products["status"]; ?>"><?php echo $fetch_products["status"]; ?></option>
                            <option value="active">active</option>
                            <option value="inactive">inactive</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>Product Name</label>
                        <input type="text" name="name" value="<?php echo $fetch_products["name"]; ?>">
                    </div>
                    <div class="input-field">
                        <label>Product Price</label>
                        <input type="text" name="price" value="<?php echo $fetch_products["price"]; ?>">
                    </div>
                    <div class="input-field">
                        <label>Product Description</label>
                        <input type="text" name="content" value="<?php echo $fetch_products["product_detail"]; ?>">
                    </div>
                    <div class="input-field">
                        <label>Product Image</label>
                        <input type="file" name="image" accept="image/*">
                        <img src="../image/<?php echo $fetch_products["image"]; ?>">
                    </div>
                    <div class="flex-btn">
                        <button type="submit" name="update" class="btn">Update Product</button>
                        <a href="view-product.php" class="btn">Cancel</a>
                        <button type="submit" name="delete" class="btn">Delete Product</button>
                    </div>
                </form>
            </div>
            <?php
                }
            } else {
                echo '<div class="empty">
                        <p>No Products Added Yet! <br /> <a href="add-product.php" style="margin-top: 1.5rem;" class="btn">Add Product</a></p>
                    </div>';
            }
                ?>
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