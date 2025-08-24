<!-- <?php
session_start();
?> -->
<style>
    .header {
        background-color:rgb(255, 255, 255);
        color: black;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .header .logo-section {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .header .logo {
        font-size: 24px;
        font-weight: bold;
        color: black;
        text-decoration: none;
    }
    
    .header .logo:hover {
        color: grey;
        text-decoration: none;
    }
    /* Ensure perfect vertical centering for header contents */
    .header .logo {
        line-height: 1;
        display: inline-flex;
        align-items: center;
    }
    .header .logo-section img {
        display: block;
    }

    .header .logo-info {
        display: flex;
        align-items: center;
        padding: 0px 10px;
        color: black;
        text-decoration: none;
        background-color: #ffffff;
        border-radius: 10px;
        
    }
    
    .header .logo-info:hover {
        opacity: 0.8;
        color:grey;
        
        text-decoration: none;
    }
    
    .header .user-section {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .header .user-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: black;
        text-decoration: none;
    }
    
    .header .user-info:hover {
        opacity: 0.8;
        color: grey;
        text-decoration: none;
    }
    
    .header .profile-icon {
        font-size: 20px;
        width: 35px;
        height: 35px;
        background-color: rgba(80, 47, 226, 0.18);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .header .welcome-text {
        font-size: 14px;
        line-height: 1;
    }
    
    .header .username {
        font-size: 14px;
        font-weight: 500;
        line-height: 1;
    }

    .header .logout-btn {
        padding: 8px 12px;
        background-color: #e74c3c;
        color: #ffffff;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: 1px solid rgba(255,255,255,0.2);
        transition: opacity 0.2s ease;
        display: inline-flex;
        align-items: center;
    }

    .header .logout-btn:hover {
        opacity: 0.9;
    }
    
    @media (max-width: 640px) {
        .header { padding: 12px 16px; }
        .header .logo { font-size: 18px; }
        .header .profile-icon { width: 32px; height: 32px; font-size: 16px; }
        .header .username { font-size: 13px; }
        .header .logout-btn { padding: 6px 10px; font-size: 13px; }
    }
</style>

<header class="header">
    <div class="logo-info">
        <div>
            <img src="img/logo_genbi_tegal.png" alt="logo" style="width: 50px; height: 50px; ">
        </div>
        <div>
            <a href="#" class="logo">Pemira GenBI</a>
        </div>
       
        
    </div>
    
    <div class="user-section">
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- User is logged in -->
            <div class="user-info">
                <div class="profile-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span class="username">
                    <?php 
                    if (isset($_SESSION['npm'])) {
                        echo $_SESSION['npm'];
                    } else {
                        echo "User";
                    }
                    ?>
                </span>
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>

        <?php else: ?>
            <!-- User is not logged in -->
            <div class="user-info">
                <div class="profile-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span class="welcome-text">Selamat Datang</span>
            </div>
        <?php endif; ?>
    </div>
</header>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
