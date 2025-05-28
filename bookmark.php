<?php
session_start();
include('connection.php'); 

header('Content-Type: text/html; charset=UTF-8');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; 
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmark - InfoLomba</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="./style/bookmark.css">
</head>
<body>
  
    <section id="Home">
        <nav>
            <div class="logo">
                <img src="image/logo.png" alt="Logo">
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="bookmark.php"><i class="fa-regular fa-bookmark"></i> Bookmark</a></li>
                <li><a href="about.php">About Us</a></li>
                <li class="dropdown">
                    <button class="btn">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <div class="dropdown-content">
                    <p>Selamat Datang, <b><?= htmlspecialchars($username); ?></b></p>
                    <a href="rsvp.php">My Event</a>
                    <a href="login1.php">Logout</a> 
                </div>
                </li>
            </ul>
        </nav>
    </section>

    <main class="main">
        <section class="hero">
            <h1>Bookmark</h1>
        </section>
        <section class="bookmark-info">
            <h1>Bookmark Anda</h1>
            <br><br>
            <?php if (!empty($bookmarked_events)): ?>
                <?php foreach ($bookmarked_events as $event): ?>
                    <div class="bookmark-event">
                        <h3><?= htmlspecialchars($event['name']); ?></h3>
                        <p><strong>Tanggal:</strong> <?= htmlspecialchars($event['date']); ?></p>
                        <p><strong>Lokasi:</strong> <?= htmlspecialchars($event['location']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada info lomba yang disimpan.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="logo">Winzone</div>
            <div class="links">
                <a href="#">Kontak Kami</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Pasang Iklan</a>
            </div>
            <div class="socials">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-telegram"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>
