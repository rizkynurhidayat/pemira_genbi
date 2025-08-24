<?php
session_start();
include "db.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM candidates WHERE id=$id");
$candidate = $res->fetch_assoc();

if (!$candidate) {
    die("Kandidat tidak ditemukan");
}

if (isset($_POST['update'])) {
    $nama_ketua  = $_POST['nama_ketua'];
    $nama_wakil  = $_POST['nama_wakil'];
    $universitas = $_POST['universitas'];
    $visi        = $_POST['visi'];
    $misi        = $_POST['misi'];

    $foto = $candidate['foto']; // default tetap

    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "uploads/";
        $filename   = time() . "_" . basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                // hapus foto lama
                if ($candidate['foto'] && file_exists("uploads/".$candidate['foto'])) {
                    unlink("uploads/".$candidate['foto']);
                }
                $foto = $filename;
            }
        }
    }

    $conn->query("UPDATE candidates 
                  SET nama_ketua='$nama_ketua', nama_wakil='$nama_wakil',
                      universitas='$universitas', visi='$visi', misi='$misi', foto='$foto'
                  WHERE id=$id");

    header("Location: manage_candidates.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Kandidat</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "header.php"; ?>
<div class="container">
    <h2>Edit Kandidat</h2>
    <form method="post" enctype="multipart/form-data">
            <input type="text" name="nama_ketua" value="<?= $candidate['nama_ketua'] ?>" required>
            <input type="text" name="nama_wakil" value="<?= $candidate['nama_wakil'] ?>" required>
            <input type="text" name="universitas" value="<?= $candidate['universitas'] ?>" required>
            <label>Visi</label>
            <textarea name="visi" required><?= $candidate['visi'] ?></textarea>
            <label>Misi</label>
            <textarea name="misi" required><?= $candidate['misi'] ?></textarea>
            <p>Foto sekarang:</p>
            <?php if ($candidate['foto']) { ?>
                <img src="uploads/<?= $candidate['foto'] ?>" alt="foto" style="width:100px;"><br>
            <?php } ?>
            <input type="file" name="foto" accept="image/*">
            <button type="submit" name="update" class="btn btn-primary">Update Kandidat</button>
        </form>
    <br>
    <a href="manage_candidates.php" class="btn" style="width: auto;">⬅️ Kembali</a>
</div>
<?php include "footer.php"; ?>
</body>
</html>
