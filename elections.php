<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Jenis Pemilihan</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="vote-page">
<?php include "header.php"; ?>
<div class="vote-container election-options">
    <h2 class="vote-title">Pilih Jenis Pemilihan</h2>
    <div class="cards-grid">
        <a class="btn btn-primary" href="vote.php?mode=presiden">Pemilihan Presiden Genbi (Karesidenan Pekalongan)</a>
        <a class="btn" href="vote.php?mode=komisariat&universitas=Universitas Pancasakti Tegal">Komisariat Universitas Pancasakti Tegal</a>
        <a class="btn" href="vote.php?mode=komisariat&universitas=Universitas Pekalongan">Komisariat Universitas Pekalongan</a>
        <a class="btn" href="vote.php?mode=komisariat&universitas=UIN K.H. Abdurrahman Wahid Pekalongan">Komisariat UIN K.H. Abdurrahman Wahid</a>
        <a class="btn" href="vote.php?mode=komisariat&universitas=Institut Bakti Negara Tegal">Komisariat Institut Bakti Negara Tegal</a>
    </div>
</div>
<?php include "footer.php"; ?>
</body>
</html>


