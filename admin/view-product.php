<?php
    include "../components/connection.php";
    
    session_start();

    $admin_id = $_SESSION["admin_id"];

    if (!isset($admin_id)) {
        header("Location: login.php");
    }

    // DELETE PRODUCT IN DATABASE
    if (isset($_POST["delete"])) {
        $p_id = $_POST["product_id"];
        $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);

        $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
        $delete_product->execute([$p_id]);
        $success_msg[] = "Product deleted successfully!";
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
    <title>Green Tea Admin Panel - All Product Page</title>
</head>
<body>
    <?php include "../components/admin-header.php"; ?>
    <div class="main">
        <div class="banner">
            <h1>All Products</h1>
        </div>
        <div class="title-2">
            <a href="dashboard.php">Dashboard</a><span> / All Products</span>
        </div>
        <section class="show-post">
            <h1 class="heading">All Products</h1>
            <div class="box-container">
                <?php 
                    $select_products = $conn->prepare("SELECT * FROM `products`");
                    $select_products->execute();

                    if ($select_products->rowCount() > 0) {
                        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <form action="" method="post" class="box">
                    <input type="hidden" name="product_id" value="<?= $fetch_products["id"]; ?>">
                    <?php if($fetch_products["image"] != ""){?>
                        <img src="../image/<?= $fetch_products["image"]; ?>" class="image">
                    <?php } ?>
                    <div class="status" style="color: <?php if($fetch_products["status"]=="active"){echo "green";}else{echo "red";}?>;"><?= $fetch_products["status"]; ?></div>
                    <div class="price">₱<?= $fetch_products["price"]; ?>/-</div>
                    <div class="title"><?= $fetch_products["name"]; ?></div>
                    <div class="flex-btn">
                        <a href="edit-product.php?id=<?= $fetch_products["id"];?>" class="btn">Edit</a>
                        <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this product?');">Delete</button>
                        <a href="read-product.php?post_id=<?= $fetch_products["id"];?>" class="btn">View </a>
                    </div>
                </form>
                <?php 
                        }
                    } else {
                        echo '<div class="empty">
                                <p>No Products Added Yet! <br /> <a href="add-product.php" style="margin-top: 1.5rem;" class="btn">Add Product</a></p>
                            </div>';
                    }
                ?>
            </div>
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