<?php
session_start();
include "db.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$mode = isset($_GET['mode']) && $_GET['mode'] === 'presiden' ? 'presiden' : 'komisariat';

if (isset($_POST['add'])) {
    $nama_ketua   = $_POST['nama_ketua'];
    $nama_wakil   = $_POST['nama_wakil'];
    $universitas  = $_POST['universitas'];
    $is_presiden  = isset($_POST['is_presiden']) ? intval($_POST['is_presiden']) : 0;

    // Simpan raw HTML dari TinyMCE
    $visi = $conn->real_escape_string($_POST['visi']);
    $misi = $conn->real_escape_string($_POST['misi']);

    $foto = "";
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "uploads/";
        $filename   = time() . "_" . basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $foto = $filename;
            }
        }
    }

    $sql = "INSERT INTO candidates (nama_ketua,nama_wakil,universitas,visi,misi,foto,is_Presiden) 
            VALUES ('$nama_ketua','$nama_wakil','$universitas','$visi','$misi','$foto',$is_presiden)";
    $conn->query($sql);

    header("Location: manage_candidates.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kandidat</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tiny.cloud/1/d4jxae38i884qkl1cgvd68475tkvlpajzbgx8rflcjfk73fm/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '.richtext',
        plugins: 'lists link',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link',
        menubar: false,
        branding: false,
        height: 250
      });
    </script>
</head>
<body>
<?php include "header.php"; ?>
<div class="container">
    <h2><?php echo $mode === 'presiden' ? 'Tambah Kandidat Presiden' : 'Tambah Kandidat Komisariat'; ?></h2>
    <div class="radio-group" style="margin-bottom: 16px;">
        <a class="btn <?php echo $mode === 'komisariat' ? 'btn-primary' : '' ; ?>" href="add_candidate.php?mode=komisariat" style="width:auto;">Tambah Komisariat</a>
        <a class="btn <?php echo $mode === 'presiden' ? 'btn-primary' : '' ; ?>" href="add_candidate.php?mode=presiden" style="width:auto;">Tambah Presiden</a>
    </div>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="is_presiden" value="<?php echo $mode === 'presiden' ? '1' : '0'; ?>">
        <input type="text" name="nama_ketua" placeholder="Nama Ketua" required>
        <!-- <input type="text" name="nama_wakil" placeholder="Nama Wakil" required> -->
        <select name="universitas" required>
            <option value="" disabled selected>Pilih Universitas</option>
            <option value="Universitas Pancasakti Tegal">Universitas Pancasakti Tegal</option>
            <option value="Universitas Pekalongan">Universitas Pekalongan</option>
            <option value="UIN K.H. Abdurrahman Wahid Pekalongan">UIN K.H. Abdurrahman Wahid Pekalongan</option>
            <option value="Institut Bakti Negara Tegal">Institut Bakti Negara Tegal</option>
        </select>
        <label>Visi:</label><br>
        <textarea name="visi" class="richtext"></textarea><br><br>
        <label>Misi:</label><br>
        <textarea name="misi" class="richtext"></textarea><br><br>
        <input type="file" name="foto" accept="image/*" required>
        <button type="submit" name="add" class="btn btn-primary">Simpan Kandidat</button>
    </form>
    <br>
    <a href="manage_candidates.php" class="btn" style="width: auto;">⬅️ Kembali</a>
</div>
<?php include "footer.php"; ?>
</body>
</html>
