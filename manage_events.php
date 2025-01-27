<?php
// Mulai sesi PHP
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: register.php"); // Arahkan ke halaman login
    exit();
}

// Ambil username dan role dari sesi
$username = $_SESSION['username'];
$role = $_SESSION['role'] ?? '';

// Koneksi ke database
include 'connection.php';

// Periksa apakah formulir telah disubmit untuk menambah event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_name'])) {
    $event_name = htmlspecialchars(trim($_POST['event_name']));
    $event_date = $_POST['event_date'];
    $start_time = $_POST['start_time'] ?? '00:00:00';
    $end_time = $_POST['end_time'] ?? '00:00:00';   
    $location = htmlspecialchars(trim($_POST['location']));
    $description = htmlspecialchars(trim($_POST['description']));
    $event_image = '';

    // Proses upload gambar jika ada
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $file_tmp = $_FILES['event_image']['tmp_name'];
        $file_name = basename($_FILES['event_image']['name']);
        $file_size = $_FILES['event_image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        // Validasi ekstensi file
        if (in_array($file_ext, $allowed_ext)) {
            // Validasi ukuran file (maksimum 2MB)
            if ($file_size <= 2 * 1024 * 1024) {
                // Pastikan direktori target ada
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                // Buat nama file unik
                $new_file_name = uniqid("event_", true) . '.' . $file_ext;
                $event_image = $target_dir . $new_file_name;

                // Pindahkan file ke direktori target
                if (!move_uploaded_file($file_tmp, $event_image)) {
                    $event_image = ''; // Kosongkan jika gagal
                }
            } else {
                echo "Ukuran file terlalu besar! Maksimum 2MB.";
            }
        } else {
            echo "Format file tidak didukung! Hanya JPG, JPEG, PNG, dan GIF.";
        }
    }

    // Simpan data ke database
    // Simpan data ke database
    $sql = "INSERT INTO events (name, date, start_time, end_time, location, description, image) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
    $stmt->bind_param("sssssss", $event_name, $event_date, $start_time, $end_time, $location, $description, $event_image);
    if ($stmt->execute()) {
    $_SESSION['event_added'] = true; // Status sukses
    } else {
    $_SESSION['event_added'] = false; // Status gagal
    }
    $stmt->close();
    }


    header("Location: manage_events.php"); // Refresh halaman
    exit();
}

// Periksa apakah formulir telah disubmit untuk menghapus event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_event_id'])) {
    $event_id = $_POST['delete_event_id'];
    $event_image = $_POST['delete_event_image'];

    // Hapus event dari database
    $sql = "DELETE FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $event_id);
        if ($stmt->execute()) {
            // Hapus gambar jika ada
            if (!empty($event_image) && file_exists($event_image)) {
                unlink($event_image); // Hapus file gambar dari server
            }
            $_SESSION['event_deleted'] = true; // Berhasil dihapus
        } else {
            $_SESSION['event_deleted'] = false; // Gagal dihapus
        }
        $stmt->close();
    }

    header("Location: manage_events.php"); // Refresh halaman
    exit();
}

// Ambil daftar event
$sql = "SELECT * FROM events ORDER BY event_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Event</title>
    <link rel="stylesheet" href="style/event.css">
    <link href="https://fonts.googleapis.com/css2?family=Mansalva&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<section id="Home">
    <nav>
        <div class="logo">
            <img src="image/logo.png" alt="Logo">
        </div>
        <ul>
            <li><a href="indexadmin.php">Home</a></li>
            <li><a href="#courses">Tips</a></li>
            <li><a href="Customer.html"><i class="fa-regular fa-bookmark"></i> Bookmark</a></li>
            <li class="dropdown">
                <button class="btn">
                    <i class="fa-solid fa-user"></i>
                </button>
                <div class="dropdown-content">
                    <p>Selamat Datang, <b><?= htmlspecialchars($username); ?></b></p>
                    <?php if ($role === 'admin'): ?>
                        <a href="manage_events.php">Manage Events</a>
                        <a href="rsvp.php">RSVP</a>
                    <?php endif; ?>
                    <a href="register.php">Logout</a>
                </div>
            </li> 
        </ul>
    </nav>
</section>

<?php if ($role === 'admin'): ?>
    <section class="add-event">
    <h2>Tambah Event Baru</h2>
    <form id="addEventForm" action="" method="POST" enctype="multipart/form-data">
        <label for="event_name">Nama Event:</label>
        <input type="text" id="event_name" name="event_name" required>

        <label for="event_date">Tanggal Mulai Event:</label>
        <input type="date" id="event_date" name="event_date" required>
        
        <label for="start_time">Jam Mulai Event:</label>
        <input type="time" id="start_time" name="start_time" required>

        <label for="end_time">Jam Akhir Event:</label>
        <input type="time" id="end_time" name="end_time" required>

        <label for="location">Lokasi Event:</label>
        <input type="text" id="location" name="location" required>
        
        <label for="description">Deskripsi:</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="event_image">Gambar Event:</label>
        <input type="file" id="event_image" name="event_image" accept="image/*">

        <button type="submit">Tambah Event</button>
    </form>
</section>

<section class="event-list">
    <h2>Daftar Event</h2>
    <div class="row">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col">
                <h3><?= htmlspecialchars($row['name']); ?></h3>
                <?php if (!empty($row['image'])): ?>
                    <img src="<?= htmlspecialchars($row['image']); ?>" alt="Event Image" class="event-image">
                <?php endif; ?>
                <p><strong>Tanggal:</strong> <?= htmlspecialchars($row['date']); ?></p>
                <p><strong>Rentang Waktu:</strong> 
    <?= isset($row['start_time']) ? htmlspecialchars($row['start_time']) : 'N/A'; ?> 
    - 
    <?= isset($row['end_time']) ? htmlspecialchars($row['end_time']) : 'N/A'; ?>
</p>
                <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['location']); ?></p>
                <p><strong>Deskripsi:</strong> <?= htmlspecialchars($row['description']); ?></p>
                <form action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                    <input type="hidden" name="delete_event_id" value="<?= $row['event_id']; ?>">
                    <input type="hidden" name="delete_event_image" value="<?= htmlspecialchars($row['image']); ?>">
                    <button type="submit" class="delete-btn">Hapus</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada event yang ditambahkan.</p>
    <?php endif; ?>
</div>

</section>
<?php endif; ?>

<div class="modal" id="modalFeedback">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3 id="modalMessage"></h3>
        <button id="modalOKButton">OK</button>
    </div>
</div>

<script>
$(document).ready(function() {
    <?php if (isset($_SESSION['event_added'])): ?>
        var message = "<?= $_SESSION['event_added'] ? 'Event berhasil ditambahkan!' : 'Gagal menambahkan event.'; ?>";
        $("#modalMessage").text(message);
        $("#modalFeedback").fadeIn();
        <?php unset($_SESSION['event_added']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['event_deleted'])): ?>
        var message = "<?= $_SESSION['event_deleted'] ? 'Event berhasil dihapus!' : 'Gagal menghapus event.'; ?>";
        $("#modalMessage").text(message);
        $("#modalFeedback").fadeIn();
        <?php unset($_SESSION['event_deleted']); ?>
    <?php endif; ?>

    $(".close, #modalOKButton").click(function() {
        $("#modalFeedback").fadeOut();
    });
});
</script>

</body>
</html>
