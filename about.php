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
    <title>AboutUs - InfoLomba</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="./style/about.css">
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
            <h1>About Us</h1>
        </section>

        <section id="about" class="about">
            <div class="content">
                <h1>Tentang Perusahaan</h1>
                <p>
                    <b>InfoLomba</b> adalah sebuah platform digital yang didirikan untuk memberikan akses informasi seputar kompetisi di berbagai bidang. Dengan fokus pada transparansi dan kemudahan akses, kami bekerja sama dengan berbagai organisasi dan institusi untuk menyediakan informasi yang terpercaya dan terkini.
                </p>
                <p>
                    Sejak berdiri pada tahun 2021, InfoLomba telah membantu ribuan pengguna untuk menemukan peluang kompetisi yang sesuai dengan minat dan keahlian mereka. Kami percaya bahwa kompetisi bukan hanya tentang kemenangan, tetapi juga tentang proses belajar dan pengembangan diri.
                </p>
                <p>
                    Visi kami adalah menjadi platform kompetisi nomor satu di Indonesia yang dapat diandalkan oleh semua kalangan. Misi kami meliputi memberikan informasi terkini, menyediakan fitur bookmark yang memudahkan, dan membangun komunitas yang saling mendukung.
                </p>
            </div>
        </section>

      <section class="container">
        <h2>Our Services</h2>
        <div class="service-box">
            <i class="fa-solid fa-lightbulb"></i>
            <h3>Informasi Kompetisi</h3>
            <p>Kami menyediakan berbagai informasi tentang kompetisi akademik, kreatif, dan skill-based.</p>
        </div>
        <div class="service-box">
            <i class="fa-solid fa-bookmark"></i>
            <h3>Bookmark Lomba</h3>
            <p>Fitur bookmark kami memudahkan Anda menyimpan dan mengelola kompetisi favorit Anda.</p>
        </div>
        <div class="service-box">
            <i class="fa-solid fa-users"></i>
            <h3>Komunitas</h3>
            <p>Gabung dengan komunitas dan temukan teman satu minat untuk berkompetisi bersama.</p>
        </div>
    </section>    

    <section class="container">
        <h2>What Our Users Say</h2>
        <div class="testimonial-box">
            <p>"InfoLomba membantu saya menemukan kompetisi yang tepat dan meningkatkan skill saya!"</p>
            <strong>- Rina, Mahasiswa</strong>
        </div>
        <div class="testimonial-box">
            <p>"Platform yang luar biasa! Semua informasi lomba ada di satu tempat."</p>
            <strong>- Dimas, Freelancer</strong>
        </div>
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
