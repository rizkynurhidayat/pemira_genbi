<?php
session_start();
include "db.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

// hapus kandidat
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = $conn->query("SELECT foto FROM candidates WHERE id=$id");
    $row = $res->fetch_assoc();
    if ($row && $row['foto'] && file_exists("uploads/".$row['foto'])) {
        unlink("uploads/".$row['foto']);
    }
    $conn->query("DELETE FROM candidates WHERE id=$id");
}

$candidates = $conn->query("SELECT * FROM candidates");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Kandidat</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include "header.php"; ?>
<div class="container">
    <h2>Kelola Kandidat</h2>
    <a href="add_candidate.php" class="btn btn-primary" style="width: auto;">➕ Tambah Kandidat</a>
    <br><br>

    <h3>Daftar Kandidat</h3>
    <div class="cards-grid">
    <?php while($row = $candidates->fetch_assoc()){ ?>
        <div class="card">
            <?php 
            $imgPath = ($row['foto'] ?? '') !== '' ? ('uploads/' . $row['foto']) : '';
            if ($imgPath && file_exists($imgPath)) { ?>
            <div class="card-image">
                <img src="<?= $imgPath ?>" alt="Foto Kandidat" class="foto" onerror="this.style.display='none'">
            </div>
            <?php } ?>
            <div class="card-body">
                <h3 class="candidate-names"><?= $row['nama_ketua'] ?> & <?= $row['nama_wakil'] ?></h3>
                <p class="university"><span>Universitas:</span> <?= $row['universitas'] ?></p>
                <div class="card-actions">
                    <a class="btn btn-primary" href="edit_candidates.php?id=<?= $row['id'] ?>">✏️ Edit</a>
                    <a class="btn btn-danger delete-btn" href="manage_candidates.php?delete=<?= $row['id'] ?>" data-id="<?= $row['id'] ?>">🗑️ Hapus</a>
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
            const id = this.getAttribute('data-id');
            const explicitUrl = 'manage_candidates.php?delete=' + encodeURIComponent(id || '');
            if (window.Swal && Swal.fire) {
                e.preventDefault();
                Swal.fire({
                    title: "Yakin hapus kandidat ini?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#e74c3c",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = explicitUrl;
                    }
                });
            } else {
                // Fallback native confirm if SweetAlert not available
                const ok = window.confirm('Yakin hapus kandidat ini?');
                if (!ok) {
                    e.preventDefault();
                } else {
                    // ensure explicit navigation works even if href is relative
                    e.preventDefault();
                    window.location.href = explicitUrl;
                }
            }
        });
    });
});
</script>

<?php include "footer.php"; ?>
</body>
</html>
