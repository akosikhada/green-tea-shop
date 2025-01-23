<?php
    include "../components/connection.php";
    
    session_start();

    if (isset($_POST["login"])) {

        $email = $_POST["email"];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $password = sha1($_POST["password"]);
        $password = filter_var($password, FILTER_SANITIZE_STRING);

        $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE email =? AND password =?");
        $select_admin->execute([$email, $password]);

        if ($select_admin->rowCount() > 0) {
            $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
            $_SESSION["admin_id"] = $fetch_admin_id["id"];
            header("Location: dashboard.php");
        } else {
            $warning_msg[] = "Incorrect username or password";
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
    <title>Green Tea Admin Panel - Login Page</title>
</head>
<body>
    <div class="main">
        <section>
            <div class="form-container" id="admin-login">
                <form action="" method="post" enctype="multipart/form-data">
                    <h3>Login Now</h3>
                    <div class="input-field">
                        <label for="">Email <sup>*</sup></label>
                        <input type="text" name="email" maxlength="99999" required placeholder="Enter your email address" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <div class="input-field">
                        <label for="">Password <sup>*</sup></label>
                        <input type="password" name="password" maxlength="16" required placeholder="Enter your password" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <button type="submit" name="login" class="btn">Login</button>
                    <p>Don't have an account? <a href="register.php">Register Now</a></p>
                </form>
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