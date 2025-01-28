<?php
session_start();
include('connection.php');

// Pastikan pengguna sudah login
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: login1.php');
    exit;
}

// Ambil informasi user dari session
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Query untuk admin melihat semua RSVP
if ($role === 'admin') {
    $sql = "SELECT rsvp.rsvp_id, rsvp.user_id, rsvp.name, rsvp.email, rsvp.no_telp, rsvp.nama_sekolah, 
                   events.event_id, events.name AS event_name, events.date, events.start_time, 
                   events.end_time, events.location, events.description
            FROM rsvp
            JOIN events ON rsvp.event_id = events.event_id";
} else {
    // Query untuk user hanya melihat RSVP miliknya
    $sql = "SELECT rsvp.rsvp_id, rsvp.user_id, rsvp.name, rsvp.email, rsvp.no_telp, rsvp.nama_sekolah, 
                   events.event_id, events.name AS event_name, events.date, events.start_time, 
                   events.end_time, events.location, events.description
            FROM rsvp
            JOIN events ON rsvp.event_id = events.event_id
            WHERE rsvp.user_id = ?";
}

$stmt = $conn->prepare($sql);

if ($role !== 'admin') {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Event</title>
    <link rel="stylesheet" href="style/rsvp.css">
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
        margin-top: 5rem;
        font-size: 2rem;
        color: #fff;
        margin-bottom: 20px;
        text-align: center;
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
            <li><a href="<?= ($role === 'admin') ? 'indexadmin.php' : 'index.php'; ?>">Home</a></li>
            <li class="dropdown">
                <button class="btn">
                    <i class="fa-solid fa-user"></i>
                </button>
                <div class="dropdown-content">
                    <p>Selamat Datang, <b><?= htmlspecialchars($username); ?></b></p>
                    <?php if ($role === 'admin'): ?>
                        <a href="manage_events.php">Manage Events</a>
                    <?php endif; ?>
                    <a href="myevent.php">RSVP</a>
                    <a href="login1.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
</section>

<div class="container">
    <h1 class="title">RSVP DATA</h1>
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>RSVP ID</th>
                    <th>Event Name</th>
                    <th>Event ID</th>
                    <?php if ($role === 'admin'): ?>
                        <th>User ID</th>
                    <?php endif; ?>
                    <th>Name</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Nama Sekolah</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['rsvp_id']); ?></td>
                        <td><?= htmlspecialchars($row['event_name']); ?></td>
                        <td><?= htmlspecialchars($row['event_id']); ?></td>
                        <?php if ($role === 'admin'): ?>
                            <td><?= htmlspecialchars($row['user_id']); ?></td>
                        <?php endif; ?>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['no_telp']); ?></td>
                        <td><?= htmlspecialchars($row['nama_sekolah']); ?></td>
                    </tr>
                <?php endwhile; ?>
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
