<?php
    include "../components/connection.php";
    
    session_start();

    $admin_id = $_SESSION["admin_id"];

    if (!isset($admin_id)) {
        header("Location: login.php");
    }

    $get_id = $_GET["post_id"];

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
    <title>Green Tea Admin Panel - Read Product Page</title>
</head>
<body>
    <?php include "../components/admin-header.php"; ?>
    <div class="main">
        <div class="banner">
            <h1>Read Product</h1>
        </div>
        <div class="title-2">
            <a href="dashboard.php">Dashboard</a><span> / Read Product</span>
        </div>
        <section class="read-post">
            <h1 class="heading">Read Product</h1>
            <?php 
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                $select_products->execute([$get_id]);

                if ($select_products->rowCount() > 0) {
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form action="" method="post">
                <input type="hidden" name="product_id" value="<?= $fetch_products["id"]; ?>">
                <div class="status" style="color: <?php if($fetch_products["status"]=="active"){echo "green";}else{echo "red";}?>;"><?= $fetch_products["status"]; ?></div>
            
            <?php if($fetch_products["image"] != ""){?>
            <img src="../image/<?php echo $fetch_products["image"]; ?>" class="image">
            <?php } ?>
            <div class="price">₱<?= $fetch_products["price"]; ?>/-</div>
            <div class="title"><?= $fetch_products["name"]; ?></div>
            <div class="content"><?= $fetch_products["product_detail"]; ?></div>
            <div class="flex-btn">
                <a href="edit-product.php?id=<?= $fetch_products["id"]; ?>" class="btn">Edit</a>
                <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this product?');">Delete</button>
                <a href="view-product.php?id=<?= $get_id; ?>" class="btn">Back</a>
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