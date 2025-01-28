<?php
session_start();
include('connection.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Ambil username dan user_id dari sesi
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Ambil data RSVP dari database jika user_id tersedia
$rsvpData = [];
if ($user_id) {
    $sql = "SELECT rsvp_id, event_id, name, email, no_telp, nama_sekolah FROM rsvp WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Simpan hasil query ke dalam array
    while ($row = $result->fetch_assoc()) {
        $rsvpData[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Lomba</title>

    <link rel="stylesheet" href="style/rsvp.css">
    <link href="https://fonts.googleapis.com/css2?family=Mansalva&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f4f4f4;
    color: #333;
    font-weight: bold;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.title {
    font-size: 2rem;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
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
                <li><a href="indexadmin.php">Home</a></li>
                <li class="dropdown">
                    <button class="btn">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <div class="dropdown-content">
                        <p>Selamat Datang, <b><?= htmlspecialchars($username); ?></b></p>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                            <a href="manage_events.php">Manage Events</a>
                            <a href="rsvp.php">RSVP</a>
                        <?php endif; ?>

                        <a href="login1.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </section>

    <div class="container">
    <h1 class="title">Daftar RSVP</h1>
    <?php if (!empty($rsvpData)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Nama Sekolah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rsvpData as $rsvp): ?>
                    <tr>
                        <td><?= htmlspecialchars($rsvp['rsvp_id']); ?></td>
                        <td><?= htmlspecialchars($rsvp['event_id']); ?></td>
                        <td><?= htmlspecialchars($rsvp['name']); ?></td>
                        <td><?= htmlspecialchars($rsvp['email']); ?></td>
                        <td><?= htmlspecialchars($rsvp['no_telp']); ?></td>
                        <td><?= htmlspecialchars($rsvp['nama_sekolah']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Belum ada data RSVP yang tersedia.</p>
    <?php endif; ?>
</div>

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

    <script src="main.js"></script>
</body>
</html>
