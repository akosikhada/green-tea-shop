<header class="header">
    <div class="flex">
        <a href="dashboard.php" class="logo"><img src="../img/logo.jpg" alt="Green Tea Logo"></a>
        <nav class="navbar">
            <a href="dashboard.php">Dashboard</a>
            <a href="add-product.php">Add Product</a>
            <a href="view-product.php">View Product</a>
            <a href="user-accounts.php">Accounts</a>
        </nav>
        <div class="icons">
            <i class="bx bxs-user" id="user-btn"></i>
            <i class="bx bx-list-plus" id="menu-btn"></i>
        </div>
        <div class="profile-detail">
            <?php
                $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
                $select_profile->execute([$admin_id]);
                
                if ($select_profile->rowCount() > 0) {
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="profile">
                <img src="../image/<?= $fetch_profile['profile']; ?>" alt="Profile" class="logo-img">
                <p><?= $fetch_profile['name']; ?></p>
            </div>
            <div class="flex-btn">
                <a href="profile.php" class="btn">Profile</a>
                <a href="../components/admin-logout.php" onclick="return confirm('Logout from this website?');" class="btn">Logout</a>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</header>