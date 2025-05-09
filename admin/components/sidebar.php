<?php
include '../../config/db.php';

$admin_email = $_SESSION['admin_email'] ?? 'admin@gmail.com';

$sql = "SELECT name, email, admin_img FROM admins WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();

$admin = $result->fetch_assoc();

$adminName = $admin['name'] ?? 'Admin';
$adminEmail = $admin['email'] ?? 'admin@gmail.com';
$adminImage = $admin['admin_img'] ?? null;

$profileImgSrc = $adminImage ? 'data:image/jpeg;base64,' . base64_encode($adminImage) : '../images/profile.png';
?>

<aside class="sidebar">
    <div class="sidebar-header">
        <img src="<?= $_SESSION['admin_img'] ? 'data:image/jpeg;base64,' . base64_encode($_SESSION['admin_img']) : '../images/profile.png' ?>"
            alt="Admin Image" />
        <div>
            <h2><?= htmlspecialchars($adminName) ?></h2>
            <p class="admin-email"><?= htmlspecialchars($adminEmail) ?></p>
        </div>
    </div>
    <ul class="sidebar-links">
        <h4>
            <span>Menu</span>
            <div class="menu-separator"></div>
        </h4>
        <li>
            <a href="../tabs/dashboard.php">
                <span class="material-symbols-outlined"> dashboard </span>Dashboard</a>
        </li>
        <li>
            <a href="../tabs/petsmanagement.php"><span class="material-symbols-outlined"> pets </span>Pets</a>
        </li>
        <li>
            <a href="../tabs/staff-and-staff-admin-account.php"><span class="material-symbols-outlined"> group
                </span>Staff and Staff-Admin</a>
        </li>
        <li>
            <a href="../tabs/adopters-account.php"><span class="material-symbols-outlined"> group
                </span>Adopters</a>
        </li>
        <li>
            <a href="../tabs/application.php"><span class="material-symbols-outlined"> group
                </span>Applications</a>
        </li>
        <!-- <li>
            <a href="../tabs/staff-accounts-management.php"><span class="material-symbols-outlined"> person_apron
                </span>Staffs</a>
        </li>
        <li>
            <a href="../tabs/staff-admins-management.php"><span class="material-symbols-outlined"> person_4
                </span>Staff Admins</a>
        </li> -->
        <li>
            <a href="../tabs/shelters-management.php"><span class="material-symbols-outlined"> map </span>Shelters</a>
        </li>
        <li>
            <a href="#"><span class="material-symbols-outlined"> report </span>Reports</a>
        </li>

        <h4 style="margin-top: 2rem">
            <span>Others</span>
            <div class="menu-separator"></div>
        </h4>

        <li>
            <a href="../logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
        </li>
    </ul>
</aside>