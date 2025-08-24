<?php
session_start();
include "db.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $res->fetch_assoc();

if (!$user) {
    die("User tidak ditemukan");
}

if (isset($_POST['update'])) {
    $npm = $_POST['npm'];
    $role = $_POST['role'];

    $passwordUpdate = "";
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $passwordUpdate = ", password='$password'";
    }

    $conn->query("UPDATE users 
                  SET npm='$npm', role='$role' $passwordUpdate
                  WHERE id=$id");

    header("Location: manage_users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit User</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "header.php"; ?>
<div class="container">
    <h2>Edit User</h2>
    <div class="card" style="padding: 10px;">
        <form method="post">
            <input type="text" name="npm" value="<?= $user['npm'] ?>" required>
            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ubah password">
            <select name="role">
                <option value="user" <?= $user['role']=="user"?"selected":"" ?>>User</option>
                <option value="admin" <?= $user['role']=="admin"?"selected":"" ?>>Admin</option>
            </select>
            <button type="submit" name="update" class="btn btn-primary">Update User</button>
        </form>
    </div>
    <br>
    <a href="manage_users.php" class="btn" style="width: auto;">⬅️Kembali</a>
    <br>
    <br>
</div>
<?php include "footer.php"; ?>
</body>
</html>
