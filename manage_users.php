<?php
session_start();
include "db.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

// tambah user
if (isset($_POST['add'])) {
    $npm = $_POST['npm'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];
    $conn->query("INSERT INTO users (npm,password,role) VALUES ('$npm','$password','$role')");
}

// hapus user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
}

// update user
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $npm = $_POST['npm'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $conn->query("UPDATE users SET npm='$npm', password='$password', role='$role' WHERE id=$id");
    } else {
        $conn->query("UPDATE users SET npm='$npm', role='$role' WHERE id=$id");
    }
}

$users = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola User</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include "header.php"; ?>
<div class="container">
    <h2>Kelola User</h2>
    <div class="card" style="padding: 10px;">
        <h3>Tambah User</h3>
        <form method="post">
            <input type="text" name="npm" placeholder="Nama" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="add" class="btn btn-primary">Tambah User</button>
        </form>
    </div>

    <h3>Daftar User</h3>
    <div class="cards-grid">
    <?php while($row = $users->fetch_assoc()){ ?>
        <div class="card" >
            <div class="card-body" >
                <h3 class="candidate-names"><?= $row['npm'] ?></h3>
                <p class="university"><span>Role:</span> <?= $row['role'] ?></p>
                <div class="card-actions">
                    <a class="btn btn-primary" href="edit_user.php?id=<?= $row['id'] ?>">✏️ Edit</a>
                    <a class="btn btn-danger delete-btn" href="?delete=<?= $row['id'] ?>" data-id="<?= $row['id'] ?>">🗑️ Hapus</a>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>

    <br>
    <a href="admin.php" class="btn" style="width: auto;">⬅️ Kembali</a>
</div>

<script>
// Konfirmasi delete dengan SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            console.log('Delete button clicked for ID:', id);
            
            Swal.fire({
                title: "Yakin hapus user ini?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e74c3c",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Confirmed delete for ID:', id);
                    window.location.href = "?delete=" + id;
                }
            });
        });
    });
});
</script>

<?php include "footer.php"; ?>
</body>
</html>
