<?php
session_start();
include "db.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $npm = $conn->real_escape_string($_POST['npm']);
    $password = md5($_POST['password']);

    $result = $conn->query("SELECT * FROM users WHERE npm='$npm' AND password='$password'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['has_voted'] = $user['has_voted'];
        if (isset($user['has_voted_Presiden'])) {
            $_SESSION['has_voted_Presiden'] = $user['has_voted_Presiden'];
        }
        $_SESSION['npm'] = $user['npm'];

        if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: elections.php");
        }
        exit;
    } else {
        $error = "NPM atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Pemilih</title>
    <link rel="stylesheet" href="css/style.css">
</head>


<body class="login-page">

<?php include "header.php"; ?>

<div class="login-container">

    <div class="login-left">
        <div class="logo-section">
            <div class="logo">
                <h1>PEMIRA GENBI</h1>
                <p>Pemilihan Raya Generasi Baru Indonesia</p>
            </div>
        </div>
        <div class="info-section">
            <h3>Tata Cara Memilih</h3>
            <ol>
                <li>Masukkan Nama dan password Anda</li>
                <li>Klik tombol "Login" untuk masuk ke sistem</li>
                <li>Pilih kandidat yang Anda dukung</li>
                <li>Klik "Submit Vote" untuk mengirimkan suara</li>
                <li>Logout setelah selesai memilih</li>
            </ol>
            <div class="note">
                <strong>Catatan:</strong> Setiap mahasiswa hanya dapat memberikan suara satu kali.
            </div>
        </div>
    </div>
    
    <div class="login-right">
        <div class="login-form-container">
            <div style="align-items: center; gap: 10px; width: 100%; display: flex; justify-content: center;">
                <img src="img/logo_genbi_tegal.png" alt="logo" style="width: 200px; height: 200px; margin-bottom: 20px; ">
            </div>
            <!-- <h2>Pemira GenBI</h2> -->
            <?php if($error) echo "<p class='error'>$error</p>"; ?>
            <form method="post" class="login-form">
                <div class="form-group">
                    <label for="npm">Nama</label>
                    <input type="text" id="npm" name="npm" placeholder="Masukkan Nama" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
</body>
</html>