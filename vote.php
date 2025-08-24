<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Determine election mode and enforce vote flags
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'komisariat';
$universitasFilter = isset($_GET['universitas']) ? $_GET['universitas'] : null;

if ($mode === 'presiden') {
    if (!empty($_SESSION['has_voted_Presiden'])) {
        header("Location: already_voted.php");
        exit;
    }
} else {
    if (!empty($_SESSION['has_voted'])) {
        header("Location: already_voted.php");
        exit;
    }
}

$where = [];
if ($mode === 'presiden') {
    $where[] = "is_Presiden = 1";
} else {
    $where[] = "is_Presiden = 0";
    if ($universitasFilter) {
        $universitasEsc = $conn->real_escape_string($universitasFilter);
        $where[] = "universitas = '" . $universitasEsc . "'";
    }
}
$whereSql = count($where) ? ('WHERE ' . implode(' AND ', $where)) : '';
$candidates = $conn->query("SELECT * FROM candidates $whereSql");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemilihan Ketua & Wakil BEM</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="vote-page">
<?php include "header.php"; ?>
<div class="vote-container">
    <h2 class="vote-title"><?php echo ($mode === 'presiden') ? 'Pemilihan Presiden Genbi' : 'Pemilihan Ketua Komisariat'; ?></h2>
    <div class="cards-grid">
    <?php while ($row = $candidates->fetch_assoc()) { ?>
        <div class="card">
            <div class="card-image">
                <img src="uploads/<?= $row['foto'] ?>" alt="Foto kandidat" class="foto">
            </div>
            <div class="card-body">
                <h3 class="candidate-names"><?= $row['nama_ketua'] ?> & <?= $row['nama_wakil'] ?></h3>
                <p class="university"><span>Universitas:</span> <?= $row['universitas'] ?></p>
                <div class="card-actions">
                    <button type="button" class="btn visi-btn"
                        data-visi="<?= htmlspecialchars($row['visi'], ENT_QUOTES) ?>"
                        data-misi="<?= htmlspecialchars($row['misi'], ENT_QUOTES) ?>">
                        Visi & Misi
                    </button>
                    <form method="post" action="process_vote.php" class="vote-form">
                        <input type="hidden" name="candidate_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES) ?>">
                        <button type="submit" class="btn btn-primary vote-btn">Pilih</button>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>

<script>
// popup visi & misi dengan richtext
// ambil semua tombol visi-btn
document.querySelectorAll('.visi-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const visi = this.getAttribute('data-visi');
        const misi = this.getAttribute('data-misi');
        
        Swal.fire({
            title: "Visi & Misi",
            html: `
                <h4><b>Visi</b></h4>
                <div style="text-align:center;">${visi}</div>
                <br>
                <h4><b>Misi</b></h4>
                <div style="text-align:left; padding: 20px;">${misi}</div>
            `,
            width: "600px",
            confirmButtonText: "Tutup"
        });
    });
});



// konfirmasi sebelum vote
document.querySelectorAll('.vote-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Yakin pilih pasangan ini?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Pilih",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
<?php include "footer.php"; ?>
</body>
</html>
