<?php
    include "../components/connection.php";
    
    session_start();

    $admin_id = $_SESSION["admin_id"];

    if (!isset($admin_id)) {
        header("Location: login.php");
    }

    if (isset($_POST["delete"])) {
        $delete_id = $_POST["delete_id"];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
        
        $verify_delete = $conn->prepare("SELECT * FROM `message` WHERE id = ?");
        $verify_delete->execute([$delete_id]);

        if($verify_delete->rowCount() > 0) {
            $delete_message = $conn->prepare("DELETE FROM `message` WHERE id = ?");
            $delete_message->execute([$delete_id]);
            $message[] = "Message deleted successfully!";
        } else {
            $message[] = "Message already deleted!";
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
    <title>Green Tea Admin Panel - Messages Page</title>
</head>
<body>
    <?php include "../components/admin-header.php"; ?>
    <div class="main">
        <div class="banner">
            <h1>Messages</h1>
        </div>
        <div class="title-2">
            <a href="dashboard.php">Dashboard</a><span> / Messages</span>
        </div>
        <section class="messages">
            <h1 class="heading">Messages</h1>
            <div class="box-container">
                <?php 
                    $select_message = $conn->prepare("SELECT * FROM `message`");
                    $select_message->execute();

                    if ($select_message->rowCount() > 0) {
                        while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){
                            $user_id = $fetch_message["id"];
                ?>
                <div class="box">
                    <h3 class="name"> <?= $fetch_message["name"]; ?></h3>
                    <h4><?= $fetch_message["subject"]; ?></h4>
                    <p><?= $fetch_message["message"]; ?></p>
                    <form action="" method="post" class="flex-btn">
                        <input type="hidden" name="delete_id" value="<?= $fetch_message["id"]; ?>">
                        <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this message?');">Delete</button>
                    </form>
                </div>
                <?php
                        }
                    } else {
                        echo '<div class="empty">
                            <p>No Message Added Yet!</p>
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