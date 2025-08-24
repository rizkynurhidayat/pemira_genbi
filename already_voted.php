<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!$_SESSION['has_voted']) {
    header("Location: vote.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terima Kasih - Pemira Genbi</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include "header.php"; ?>
<div class="container">
    <div class="thank-you-card">
        <div class="thank-you-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Terima Kasih! 💖</h2>
        <p class="thank-you-message">Anda telah berpartisipasi dalam pemilihan Ketua & Wakil GenBI</p>
        <p class="vote-info">Suara Anda sangat berarti untuk kemajuan GenBI</p>
        
        <!-- <div class="action-buttons">
            <a href="logout.php" class="btn btn-primary">Logout</a>
        </div> -->
    </div>
</div>

<style>
.thank-you-card {
    text-align: center;
    padding: 40px 20px;
    max-width: 500px;
    margin: 0 auto;
}

.thank-you-icon {
    font-size: 80px;
    color: #28a745;
    margin-bottom: 20px;
}

.thank-you-card h2 {
    color: #2d2a43;
    margin-bottom: 16px;
    font-size: 2rem;
}

.thank-you-message {
    font-size: 1.1rem;
    color: #4a4a5e;
    margin-bottom: 12px;
    line-height: 1.6;
}

.vote-info {
    font-size: 0.95rem;
    color: #6b6a75;
    margin-bottom: 30px;
    line-height: 1.5;
}

.action-buttons {
    margin-top: 30px;
}

.action-buttons .btn {
    padding: 14px 30px;
    font-size: 1rem;
    font-weight: 600;
}

@media (max-width: 640px) {
    .thank-you-card {
        padding: 30px 16px;
    }
    
    .thank-you-icon {
        font-size: 60px;
        margin-bottom: 16px;
    }
    
    .thank-you-card h2 {
        font-size: 1.5rem;
        margin-bottom: 12px;
    }
    
    .thank-you-message {
        font-size: 1rem;
    }
    
    .vote-info {
        font-size: 0.9rem;
    }
    
    .action-buttons .btn {
        padding: 12px 24px;
        font-size: 0.95rem;
    }
}
</style>

<?php include "footer.php"; ?>
</body>
</html>
