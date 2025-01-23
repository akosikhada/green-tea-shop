<?php
    include "../components/connection.php";
    
    session_start();

    $admin_id = $_SESSION["admin_id"];

    if (!isset($admin_id)) {
        header("Location: login.php");
    }

    // DELETE ORDER IN DATABASE
    if (isset($_POST["delete_order"])) {
        $order_id = $_POST["order_id"];
        $order_id = filter_var($order_id, FILTER_SANITIZE_STRING);
        
        $verify_delete = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
        $verify_delete->execute([$order_id]);

        if($verify_delete->rowCount() > 0) {
            $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
            $delete_order->execute([$order_id]);
            $message[] = "Order deleted successfully!";
        } else {
            $message[] = "Order already deleted!";
        }
    }

    // UPDATE ORDER IN DATABASE
    if (isset($_POST["update_order"])) {
        $order_id = $_POST["order_id"];
        $order_id = filter_var($order_id, FILTER_SANITIZE_STRING);

        $update_payment = $_POST["update_payment"];
        $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);

        $update_order = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
        $update_order->execute([$update_payment, $order_id]);
        $message[] = "Order updated successfully";
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
    <title>Green Tea Admin Panel - Order Page</title>
</head>
<body>
    <?php include "../components/admin-header.php"; ?>
    <div class="main">
        <div class="banner">
            <h1>Orders</h1>
        </div>
        <div class="title-2">
            <a href="dashboard.php">Dashboard</a><span> / Orders</span>
        </div>
        <section class="orders">
            <h1 class="heading">Orders</h1>
            <div class="box-container">
                <?php 
                    $select_order = $conn->prepare("SELECT * FROM `orders`");
                    $select_order->execute();

                    if ($select_order->rowCount() > 0) {
                        while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                            $user_id = $fetch_order["id"];
                ?>
                <div class="box">
                    <div class="status" style="color: <?php if($fetch_order["status"]=="in progress"){echo "green";}else{echo "red";}?>;"><?= $fetch_order["status"]; ?></div>
                    <div class="detail">
                        <p>Name : <span><?= $fetch_order["name"]; ?></span></p>
                        <p>Order Date : <span><?= $fetch_order["date"]; ?></span></p>
                        <p>Phone : <span><?= $fetch_order["number"]; ?></span></p>
                        <p>Email : <span><?= $fetch_order["email"]; ?></span></p>
                        <p>Total : <span><?= $fetch_order["price"]; ?></span></p>
                        <p>Payment Method : <span><?= $fetch_order["method"]; ?></span></p>
                        <p>Address : <span><?= $fetch_order["address"]; ?></span></p>
                    </div>
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?= $fetch_order["id"]; ?>">
                        <select name="update_payment">
                            <option disabled selected><?= $fetch_order["payment_status"]; ?></option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                        <div class="flex-btn">
                            <button type="submit" name="update_order" class="btn">Update</button>
                            <button type="submit" name="delete_order" class="btn">Delete</button>
                        </div>
                    </form>
                </div>
                <?php
                        }
                    } else {
                        echo '<div class="empty">
                            <p>No Orders Placed Yet!</p>
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