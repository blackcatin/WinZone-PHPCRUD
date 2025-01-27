<?php
// Mulai sesi PHP
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: register.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Lomba</title>

    <link rel="stylesheet" href="style/script.css">
    <link href="https://fonts.googleapis.com/css2?family=Mansalva&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Dropdown Styling */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 200px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
            text-align: center;
        }

        .dropdown-content p {
            color: #3f09ac;
            padding: 10px;
            margin: 0;
            font-size: 14px;
        }

        .dropdown-content a {
            color: #3f09ac;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            font-size: 14px;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown .btn {
          display: inline-block; 
          padding: 8px 10px;
          background: #fff; 
          color: #3f09ac; 
          font-size: 16px; 
          font-weight: 600; 
          border: none; 
          border-radius: 50px; 
          text-align: center;
          text-decoration: none;
          cursor: pointer; 
          box-shadow: 0 0 10px rgba(0, 0, 0, .1); 
        }

    </style>
</head>
<body>

    <section id="Home">
        <nav>
            <div class="logo">
                <img src="image/logo.png" alt="">
            </div>

            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#courses">Tips</a></li>
                <li><a href="Customer.html"><i class="fa-regular fa-bookmark"></i> Bookmark</a></li>
                <li class="dropdown">
                    <button class="btn">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <div class="dropdown-content">
                        <p>Selamat Datang, <b><?= htmlspecialchars($username); ?></b></p>
                        <a href="register.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </section>

    <section class="header">
        <div class="container">
            <div class="title">Indonesia's <br><span class="highlight">All-Encompassing</span></div>
            <div class="subtitle">Event Center</div>

            <div class="search-bar">
                <input type="text" placeholder="Cari di sini...">
                <button>Cari</button>
            </div>
        </div>
    </section>

    <section class="lomba">
        <h1>‼️Most Wanted! Wajib Join🔥</h1>
        <div class="row" id="events"></div>
    </section>
    <?php
// Mulai sesi PHP
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: register.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Lomba</title>

    <link rel="stylesheet" href="style/script.css">
    <link href="https://fonts.googleapis.com/css2?family=Mansalva&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Dropdown Styling */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 200px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
            text-align: center;
        }

        .dropdown-content p {
            color: #3f09ac;
            padding: 10px;
            margin: 0;
            font-size: 14px;
        }

        .dropdown-content a {
            color: #3f09ac;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            font-size: 14px;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown .btn {
          display: inline-block; 
          padding: 8px 10px;
          background: #fff; 
          color: #3f09ac; 
          font-size: 16px; 
          font-weight: 600; 
          border: none; 
          border-radius: 50px; 
          text-align: center;
          text-decoration: none;
          cursor: pointer; 
          box-shadow: 0 0 10px rgba(0, 0, 0, .1); 
        }

    </style>
</head>
<body>

    <section id="Home">
        <nav>
            <div class="logo">
                <img src="image/logo.png" alt="">
            </div>

            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#courses">Tips</a></li>
                <li><a href="Customer.html"><i class="fa-regular fa-bookmark"></i> Bookmark</a></li>
                <li class="dropdown">
                    <button class="btn">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <div class="dropdown-content">
                        <p>Selamat Datang, <b><?= htmlspecialchars($username); ?></b></p>
                        <a href="register.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </section>

    <section class="header">
        <div class="container">
            <div class="title">Indonesia's <br><span class="highlight">All-Encompassing</span></div>
            <div class="subtitle">Event Center</div>

            <div class="search-bar">
                <input type="text" placeholder="Cari di sini...">
                <button>Cari</button>
            </div>
        </div>
    </section>

    <section class="lomba">
        <h1>‼️Most Wanted! Wajib Join🔥</h1>
        <div class="row" id="events"></div>
    </section>


    <script src="main.js"></script>
</body>
</html>


    <script src="main.js"></script>
</body>
</html>
