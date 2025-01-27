<?php
session_start(); // Pastikan session dimulai untuk mengambil user_id
include('connection.php'); // Pastikan koneksi database diatur di file ini

// Periksa apakah user sudah login dan mendefinisikan variabel $username
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; // Ganti dengan nilai default jika tidak ada sesi

// Proses RSVP
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rsvp'])) {
    $event_id = $_POST['event_id']; // ID event yang dipilih
    $user_id = $_SESSION['user_id']; // ID pengguna yang sedang login

    // Periksa apakah pengguna sudah melakukan RSVP untuk event ini
    $check_sql = "SELECT * FROM rsvp WHERE user_id = ? AND event_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Masukkan RSVP ke dalam database
        $rsvp_sql = "INSERT INTO rsvp (user_id, event_id, status) VALUES (?, ?, 'pending')";
        $stmt = $conn->prepare($rsvp_sql);
        $stmt->bind_param("ii", $user_id, $event_id);
        if ($stmt->execute()) {
            echo "<script>alert('RSVP berhasil!');</script>";
        } else {
            echo "<script>alert('Gagal melakukan RSVP.');</script>";
        }
    } else {
        echo "<script>alert('Anda sudah melakukan RSVP untuk event ini.');</script>";
    }
}

// Ambil data event dari database
$sql = "SELECT * FROM events";
$result = $conn->query($sql);
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
        /* Styling untuk dropdown dan RSVP button */
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

/* Styling untuk bagian lomba */
.lomba {
    padding: 40px 20px;
    background-color: #f3f6e7;
    text-align: center;
}

.lomba h1 {
    color: #3f09ac;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 40px;
}

.lomba .row {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
}

.lomba .event {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: 30%;
    transition: all 0.3s ease;
    text-align: center;
}

.lomba .event:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(104, 35, 164, 0.2);
}

.lomba .event h3 {
    color: #3f09ac;
    font-size: 1.8rem;
    margin-bottom: 15px;
    font-weight: 600;
}

.lomba .event p {
    color: #555;
    font-size: 14px;
    margin-bottom: 15px;
}

.lomba .event img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    margin-bottom: 15px;
}

/* Styling untuk tombol RSVP */
.lomba .rsvp-btn {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.lomba .rsvp-btn:hover {
    background-color: #45a049;
}

/* Styling untuk detail acara seperti Tanggal, Rentang Waktu, Lokasi, dan Deskripsi */
.lomba .event p strong {
    font-weight: 600;
    color: #3f09ac;
}


        .rsvp-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50; /* Hijau */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .rsvp-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<section id="Home">
    <nav>
        <div class="logo">
            <img src="image/logo.png" alt="Logo">
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
                    <a href="login1.php">Logout</a> <!-- Perbaiki link logout -->
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
    <div class="row">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="event">
                <h3><?= htmlspecialchars($row['name']); ?></h3>
                <?php if (!empty($row['image'])): ?>
                    <img src="<?= htmlspecialchars($row['image']); ?>" alt="Event Image" class="event-image">
                <?php endif; ?>
                <p><strong>Tanggal:</strong> <?= htmlspecialchars($row['date']); ?></p>
                <p><strong>Rentang Waktu:</strong> <?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></p>
                <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['location']); ?></p>
                <p><strong>Deskripsi:</strong> <?= htmlspecialchars($row['description']); ?></p>
                
                <!-- Tombol RSVP -->
                <form method="POST" action="">
                    <input type="hidden" name="event_id" value="<?= $row['event_id']; ?>">
                    <button type="submit" name="rsvp" class="rsvp-btn">RSVP</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada event yang ditambahkan.</p>
    <?php endif; ?>
    </div>
</section>

</body>
</html>
