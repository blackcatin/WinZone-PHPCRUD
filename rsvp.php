<?php
session_start();
include('connection.php');


if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login1.php');
    exit;
}


$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

if (!$user_id) {  
    die("Error: user_id tidak ditemukan. Pastikan Anda sudah login.");
}

$sql = "SELECT rsvp.rsvp_id, rsvp.name, rsvp.email, rsvp.no_telp, rsvp.nama_sekolah, 
               events.event_id, events.name AS event_name, events.date, events.start_time, 
               events.end_time, events.location, events.description
        FROM rsvp
        JOIN events ON rsvp.event_id = events.event_id
        WHERE rsvp.user_id = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error dalam prepare statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
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
            margin-top : 5rem;
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
            <img src="image/logo.png" alt="">
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
                    <a href="login1.php">Logout</a> <!-- Perbaiki link logout -->
                </div>
            </li>
        </ul>
    </nav>
</section>

<div class="container">
    <h1 class="title">MY EVENT</h1>
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Nama Sekolah</th>
                    <th>Ticket</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['event_name']); ?></td>
                        <td><?= htmlspecialchars($row['date']); ?></td>
                        <td><?= htmlspecialchars($row['start_time']) . ' - ' . htmlspecialchars($row['end_time']); ?></td>
                        <td><?= htmlspecialchars($row['location']); ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['no_telp']); ?></td>
                        <td><?= htmlspecialchars($row['nama_sekolah']); ?></td>
                        <td>
                            <a href="generate_ticket.php?rsvp_id=<?= $row['rsvp_id']; ?>" class="download-btn">Download Ticket</a>
                        </td>
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
