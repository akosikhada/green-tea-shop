<?php
    include "../components/connection.php";
    
    if (isset($_POST["register"])) {
        $id = unique_id();

        $name = $_POST["name"];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $email = $_POST["email"];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $password = sha1($_POST["password"]);
        $password = filter_var($password, FILTER_SANITIZE_STRING);

        $confirm_password = sha1($_POST["confirm_password"]);
        $confirm_password = filter_var($confirm_password, FILTER_SANITIZE_STRING);

        $image = $_FILES["image"]["name"];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_tmp_name = $_FILES["image"]["tmp_name"];
        $image_folder = "../image/" .$image;

        $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ?");
        $select_admin->execute([$email]);

        if ($select_admin->rowCount() > 0) {
            $warning_msg[] = "Email Address already exists";
        } else {
            if ($password != $confirm_password) {
                $warning_msg[] = "Password does not match";
            } else {
                $insert_admin = $conn->prepare("INSERT INTO `admin`(id, name, email, password, profile) VALUES(?,?,?,?,?)");
                $insert_admin->execute([$id, $name, $email, $confirm_password, $image]);
                move_uploaded_file($image_tmp_name, $image_folder);
                $success_msg[] = "Registered Successfully";
            }
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
    <title>Green Tea Admin Panel - Register Page</title>
</head>
<body>
    <div class="main">
        <section>
            <div class="form-container" id="admin-login">
                <form action="" method="post" enctype="multipart/form-data">
                    <h3>Register Now</h3>
                    <div class="input-field">
                        <label for="">Username <sup>*</sup></label>
                        <input type="text" name="name" maxlength="99999" required placeholder="Enter your username" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <div class="input-field">
                        <label for="">Email <sup>*</sup></label>
                        <input type="text" name="email" maxlength="99999" required placeholder="Enter your email address" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <div class="input-field">
                        <label for="">Password <sup>*</sup></label>
                        <input type="password" name="password" maxlength="16" required placeholder="Enter your password" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <div class="input-field">
                        <label for="">Confirm Password <sup>*</sup></label>
                        <input type="password" name="confirm_password" maxlength="16" required placeholder="Confirm your password" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <div class="input-field">
                        <label for="">Profile <sup>*</sup></label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                    <button type="submit" name="register" class="btn">Register</button>
                    <p>Already have an account? <a href="login.php">Login Now</a></p>
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