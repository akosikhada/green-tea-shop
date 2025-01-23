<?php
    include "../components/connection.php";
    
    session_start();

    $admin_id = $_SESSION["admin_id"];

    if (!isset($admin_id)) {
        header("Location: login.php");
    }

    if(isset($_POST['delete'])) {
        $user_id = $_POST['user_id'];
        $user_id = filter_var($user_id, FILTER_SANITIZE_STRING);

        $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
        $delete_user->execute([$user_id]);

        $success_msg[] = "User deleted successfully!";
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
    <title>Green Tea Admin Panel - Registered Users</title>
</head>
<body>
    <?php include "../components/admin-header.php"; ?>
    <div class="main">
        <div class="banner">
            <h1>Registered Users</h1>
        </div>
        <div class="title-2">
            <a href="dashboard.php">Dashboard</a><span> / Registered Users</span>
        </div>
        <section class="accounts">
            <h1 class="heading">Registered Users</h1>
            <div class="box-container">
                <?php 
                    $select_users = $conn->prepare("SELECT * FROM `users`");
                    $select_users->execute();

                    if ($select_users->rowCount() > 0) {
                        while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
                            $user_id = $fetch_users["id"];
                ?>
                <div class="box">
                    <p>ID : <span><?php echo $user_id; ?></span></p>
                    <p>Name : <span><?php echo $fetch_users["name"]; ?></span></p>
                    <p>Email : <span><?php echo $fetch_users["email"]; ?></span></p>
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this user?');">Delete User</button>
                    </form>
                </div>
                <?php
                        }
                    } else {
                        echo '<div class="empty">
                            <p>No Users Added Yet!</p>
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
    <?php
        if (isset($success_msg)) {
            foreach ($success_msg as $success) {
                echo '<script>swal("Success!", "'.$success.'", "success");</script>';
            }
        }
    ?>
</body>
</html>