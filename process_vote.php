<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$candidate_id = intval($_POST['candidate_id']);
$mode = isset($_POST['mode']) ? $_POST['mode'] : (isset($_GET['mode']) ? $_GET['mode'] : 'komisariat');

// cek apakah sudah vote sesuai mode
$check = $conn->query("SELECT has_voted, has_voted_Presiden FROM users WHERE id=$user_id");
$user = $check->fetch_assoc();

if ($mode === 'presiden') {
    if (!empty($user['has_voted_Presiden'])) {
        die("Kamu sudah memilih Presiden!");
    }
} else {
    if (!empty($user['has_voted'])) {
        die("Kamu sudah memilih Komisariat!");
    }
}

// simpan vote
$conn->query("INSERT INTO votes (user_id, candidate_id) VALUES ($user_id, $candidate_id)");
if ($mode === 'presiden') {
    $conn->query("UPDATE users SET has_voted_Presiden=1 WHERE id=$user_id");
    $_SESSION['has_voted_Presiden'] = 1;
} else {
    $conn->query("UPDATE users SET has_voted=1 WHERE id=$user_id");
    $_SESSION['has_voted'] = 1;
}

echo "<p style='text-align:center;'>Terima kasih sudah memilih 💖</p>";
echo "<p style='text-align:center;'><a href='logout.php'>Logout</a></p>";
?>
