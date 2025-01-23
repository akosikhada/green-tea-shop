<?php
    include "../components/connection.php";
    
    session_start();

    $admin_id = $_SESSION["admin_id"];

    if (!isset($admin_id)) {
        header("Location: login.php");
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
    <title>Green Tea Admin Panel - Dashboard Page</title>
</head>
<body>
    <?php include "../components/admin-header.php"; ?>
    <div class="main">
        <div class="banner">
            <h1>Dashboard</h1>
        </div>
        <div class="title-2">
            <a href="dashboard.php">Home</a><span> / Dashboard</span>
        </div>
        <section class="dashboard">
            <h1 class="heading">Dashboard</h1>
            <div class="box-container">
                <div class="box">
                    <h3>Welcome!</h3>
                    <p><?= $fetch_profile['name']; ?></p>
                    <a href="" class="btn">Profile</a>
                </div>
                <div class="box">
                    <?php
                        $select_product = $conn->prepare("SELECT * FROM `products`");
                        $select_product->execute();
                        $num_of_products = $select_product->rowCount();
                    ?>
                    <h3><?= $num_of_products; ?></h3>
                    <p>Products Added</p>
                    <a href="add-product.php" class="btn">Add Product</a>
                </div>
                <div class="box">
                    <?php
                        $select_active_product = $conn->prepare("SELECT * FROM `products` WHERE status = ?");
                        $select_active_product->execute(["active"]);
                        $num_of_active_products = $select_active_product->rowCount();
                    ?>
                    <h3><?= $num_of_active_products; ?></h3>
                    <p>Total Active Products</p>
                    <a href="view-product.php" class="btn">Active Products</a>
                </div>
                <div class="box">
                    <?php
                        $select_inactive_product = $conn->prepare("SELECT * FROM `products` WHERE status = ?");
                        $select_inactive_product->execute(["inactive"]);
                        $num_of_inactive_products = $select_inactive_product->rowCount();
                    ?>
                    <h3><?= $num_of_inactive_products; ?></h3>
                    <p>Total Inactive Products</p>
                    <a href="view-product.php" class="btn">Inactive Products</a>
                </div>
                <div class="box">
                    <?php
                        $select_users = $conn->prepare("SELECT * FROM `users`");
                        $select_users->execute();
                        $num_of_users = $select_users->rowCount();
                    ?>
                    <h3><?= $num_of_users; ?></h3>
                    <p>Registered Users</p>
                    <a href="user-accounts.php" class="btn">Users</a>
                </div>
                <div class="box">
                    <?php
                        $select_admin = $conn->prepare("SELECT * FROM `admin`");
                        $select_admin->execute();
                        $num_of_admin = $select_admin->rowCount();
                    ?>
                    <h3><?= $num_of_admin; ?></h3>
                    <p>Registered Admin</p>
                    <a href="accounts.php" class="btn">Admin</a>
                </div>
                <div class="box">
                    <?php
                        $select_message = $conn->prepare("SELECT * FROM `message`");
                        $select_message->execute();
                        $num_of_message = $select_message->rowCount();
                    ?>
                    <h3><?= $num_of_message; ?></h3>
                    <p>Unread Message</p>
                    <a href="message.php" class="btn">Inbox</a>
                </div>
                <div class="box">
                    <?php
                        $select_orders = $conn->prepare("SELECT * FROM `orders`");
                        $select_orders->execute();
                        $num_of_orders = $select_orders->rowCount();
                    ?>
                    <h3><?= $num_of_orders; ?></h3>
                    <p>Total Orders</p>
                    <a href="order.php" class="btn">Orders</a>
                </div>
                <div class="box">
                    <?php
                        $select_confirm_orders = $conn->prepare("SELECT * FROM `orders` WHERE status = ?");
                        $select_confirm_orders->execute(["in progress"]);
                        $num_of_confirm_orders = $select_confirm_orders->rowCount();
                    ?>
                    <h3><?= $num_of_confirm_orders; ?></h3>
                    <p>Total Confirm Orders</p>
                    <a href="order.php" class="btn">Confirm Orders</a>
                </div>
                <div class="box">
                    <?php
                        $select_cancelled_orders = $conn->prepare("SELECT * FROM `orders` WHERE status = ?");
                        $select_cancelled_orders->execute(["cancelled"]);
                        $num_of_cancelled_orders = $select_cancelled_orders->rowCount();
                    ?>
                    <h3><?= $num_of_cancelled_orders; ?></h3>
                    <p>Total Cancelled Orders</p>
                    <a href="order.php" class="btn">Cancelled Orders</a>
                </div>
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