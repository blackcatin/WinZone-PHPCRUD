<<?php
session_start();
include('connection.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get user's ID
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;


// Get events from database
$sql = "SELECT * FROM rsvp WHERE user_id = ? AND event_id IN (SELECT event_id FROM events)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Display events
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $event_id = $row['event_id'];

        // Get event details from database
        $event_sql = "SELECT * FROM events WHERE event_id = ?";
        $event_stmt = $conn->prepare($event_sql);
        $event_stmt->bind_param("i", $event_id);
        $event_stmt->execute();
        $event_result = $event_stmt->get_result();

        // Display event details
        if ($event_result->num_rows > 0) {
            while ($event_row = $event_result->fetch_assoc()) {
                echo "<h2>" . $event_row['name'] . "</h2>";
                echo "<p>Tanggal: " . $event_row['date'] . "</p>";
                echo "<p>Rentang Waktu: " . $event_row['start_time'] . " - " . $event_row['end_time'] . "</p>";
                echo "<p>Lokasi: " . $event_row['location'] . "</p>";
                echo "<p>Deskripsi: " . $event_row['description'] . "</p>";
            }
        }
    }
} else {
    echo "Belum ada event yang Anda RSVP.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Event</title>
    <link rel="stylesheet" href="style/rsvp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                    <a href="myevent.php">My Event</a>
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
                        <th>ID</th>
                        <th>Username</th>
                        <th>Event</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Nama Sekolah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['rsvp_id']); ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['event_name']); ?></td>
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
