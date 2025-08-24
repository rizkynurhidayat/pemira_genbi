<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

// ambil data voting untuk chart
$mode = isset($_GET['mode']) && $_GET['mode'] === 'presiden' ? 'presiden' : 'komisariat';
$komisariat = isset($_GET['komisariat']) ? $_GET['komisariat'] : '';

$where = [];
if ($mode === 'presiden') {
    $where[] = "c.is_Presiden = 1";
} else {
    $where[] = "c.is_Presiden = 0";
    if (!empty($komisariat)) {
        $komEsc = $conn->real_escape_string($komisariat);
        $where[] = "c.universitas = '".$komEsc."'";
    }
}
$whereSql = count($where) ? ('WHERE ' . implode(' AND ', $where)) : '';

$data = $conn->query("SELECT c.nama_ketua, c.nama_wakil, c.universitas, COUNT(v.id) as total 
                      FROM candidates c 
                      LEFT JOIN votes v ON c.id=v.candidate_id 
                      $whereSql
                      GROUP BY c.id");
$labels = [];
$values = [];
while($row = $data->fetch_assoc()){
    $labels[] = $row['nama_ketua']." & ".$row['nama_wakil'];
    $values[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include "header.php"; ?>
<div class="container">
    <h2>Dashboard Admin</h2>
    <p style="padding-bottom:20px;">Selamat datang, Admin 💖</p>

    <form method="get" class="form" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <label class="radio-group" style="margin:0;">
            <a class="btn <?php echo $mode==='komisariat' ? 'btn-primary' : ''; ?>" href="admin.php?mode=komisariat" style="width:auto;">Hasil Voting Komisariat</a>
            <a class="btn <?php echo $mode==='presiden' ? 'btn-primary' : ''; ?>" href="admin.php?mode=presiden" style="width:auto;">Hasil Voting Presiden</a>
        </label>
        <?php if ($mode==='komisariat') { ?>
        <select name="komisariat" onchange="this.form.submit()" style="min-width:260px;">
            <option value="">Semua Komisariat</option>
            <option value="Universitas Pancasakti Tegal" <?php echo $komisariat==='Universitas Pancasakti Tegal'?'selected':''; ?>>Universitas Pancasakti Tegal</option>
            <option value="Universitas Pekalongan" <?php echo $komisariat==='Universitas Pekalongan'?'selected':''; ?>>Universitas Pekalongan</option>
            <option value="UIN K.H. Abdurrahman Wahid Pekalongan" <?php echo $komisariat==='UIN K.H. Abdurrahman Wahid Pekalongan'?'selected':''; ?>>UIN K.H. Abdurrahman Wahid Pekalongan</option>
            <option value="Institut Bakti Negara Tegal" <?php echo $komisariat==='Institut Bakti Negara Tegal'?'selected':''; ?>>Institut Bakti Negara Tegal</option>
        </select>
        <input type="hidden" name="mode" value="komisariat">
        <?php } ?>
    </form>

    <div class="admin-grid" >
        <div class="card chart-card" style="padding:20px;">
    <h3>Grafik Hasil Voting</h3>
            <div class="chart-container">
    <canvas id="voteChart"></canvas>
            </div>
        </div>

        <div class="card" style="padding:20px;">
    <h3>Menu</h3>
            <div class="card-actions" >
                <a class="btn btn-primary" href="manage_candidates.php">Kelola Kandidat</a>
                <a class="btn" href="manage_users.php">Kelola User</a>
                
            </div>
        </div>
    </div>
</div>

<script>
const labels = <?= json_encode($labels) ?>;
const dataValues = <?= json_encode($values) ?>;
const palette = [
    '#667eea', '#764ba2', '#34a853', '#f39c12', '#e74c3c', '#1abc9c',
    '#9b59b6', '#2ecc71', '#e67e22', '#3498db', '#16a085', '#c0392b'
];
const colors = labels.map((_, i) => palette[i % palette.length]);

const ctx = document.getElementById('voteChart').getContext('2d');
const voteChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Jumlah Suara',
            data: dataValues,
            backgroundColor: colors.map(c => c + '88'),
            borderColor: colors,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' },
            tooltip: { enabled: true }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0 }
            }
        }
    }
});
</script>
<?php include "footer.php"; ?>
</body>
</html>
