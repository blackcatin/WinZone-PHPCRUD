<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login1.php"); 
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'] ?? '';

include 'connection.php';


$eventUpdated = $_SESSION['event_updated'] ?? null;
$eventAdded = $_SESSION['event_added'] ?? null;
$eventDeleted = $_SESSION['event_deleted'] ?? null;

unset($_SESSION['event_updated']);
unset($_SESSION['event_added']);
unset($_SESSION['event_deleted']);

function validateInput($data) {
    return htmlspecialchars(trim($data));
}

define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); 
$allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

function handleFileUpload($file, $oldFile = null) {
    global $allowed_ext;

    if (isset($file) && $file['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $file['tmp_name'];
        $file_name = basename($file['name']);
        $file_size = $file['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext) && $file_size <= MAX_FILE_SIZE) {
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0777, true);
            }

            $new_file_name = uniqid("event_", true) . '.' . $file_ext;
            $file_path = UPLOAD_DIR . $new_file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                if ($oldFile && file_exists($oldFile)) {
                    unlink($oldFile);
                }
                return $file_path;
            }
        }
    }
    return $oldFile;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['form_action'] ?? '';

    if ($action === 'insert_event') {

        $event_name = validateInput($_POST['event_name']);
        $event_date = validateInput($_POST['event_date']);
        $start_time = $_POST['start_time'] ?? '00:00:00';
        $end_time = $_POST['end_time'] ?? '00:00:00';
        $location = validateInput($_POST['location']);
        $description = validateInput($_POST['description']);
        $event_image = handleFileUpload($_FILES['event_image']);

        $sql = "INSERT INTO events (name, date, start_time, end_time, location, description, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssssss", $event_name, $event_date, $start_time, $end_time, $location, $description, $event_image);
            $_SESSION['event_added'] = $stmt->execute();
            $stmt->close();
        } else {
            $_SESSION['event_added'] = false;
        }
        header("Location: manage_events.php");
        exit();
    } elseif ($action === 'update_event') {
        $event_id = $_POST['event_id'];
        $event_name = validateInput($_POST['event_name']);
        $event_date = validateInput($_POST['event_date']);
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $location = validateInput($_POST['location']);
        $description = validateInput($_POST['description']);
        $old_image = $_POST['old_image'];
        $event_image = handleFileUpload($_FILES['edit_event_image'], $old_image);

        $sql = "UPDATE events SET name=?, date=?, start_time=?, end_time=?, location=?, description=?, image=? WHERE event_id=?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssssssi", $event_name, $event_date, $start_time, $end_time, $location, $description, $event_image, $event_id);
            $_SESSION['event_updated'] = $stmt->execute();
            $stmt->close();
        } else {
            $_SESSION['event_updated'] = false;
        }
        header("Location: manage_events.php");
        exit();
    } elseif ($action === 'delete_event') {

        $event_id = $_POST['delete_event_id'];
        $event_image = $_POST['delete_event_image'];

        $sql = "DELETE FROM events WHERE event_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $event_id);
            if ($stmt->execute() && $event_image && file_exists($event_image)) {
                unlink($event_image);
            }
            $_SESSION['event_deleted'] = true;
            $stmt->close();
        } else {
            $_SESSION['event_deleted'] = false;
        }
        header("Location: manage_events.php");
        exit();
    }
}

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

    <style>
        .modal {
        display: none; 
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6); 
        display: flex;
        justify-content: center;
        align-items: center;
        }

        .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease-in-out;
        text-align: center;
        }

        .flex-container {
        display: flex;
        gap: 10px;
        justify-content: space-between;
        }

        .flex-container div {
        flex: 1;
        }

        .flex-container input {
            width: 100%;
        }

        .modal-content label {
            font-size: 14px;
            color: #333;
            font-weight: bold;
            text-align: left;
        }

        .modal-content input,
        .modal-content textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        .modal-content input:focus,
        .modal-content textarea:focus {
            border-color: #3f09ac;
            box-shadow: 0 0 5px rgba(63, 9, 172, 0.5);
        }

        .modal-content button {
            background-color: #3f09ac;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal-content button:hover {
            background-color: #2c078b;
        }
        .edit-btn {
        background-color: #ffc107;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        margin-right: 5px;
        }

        .edit-btn:hover {
            background-color: #e0a800;
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
            <li><a href="indexadmin.php">Home</a></li>
            <li class="dropdown">
                <button class="btn">
                    <i class="fa-solid fa-user"></i>
                </button>
                <div class="dropdown-content">
                    <p>Selamat Datang, <b><?= htmlspecialchars($username); ?></b></p>
                    <?php if ($role === 'admin'): ?>
                        <a href="manage_events.php">Manage Events</a>
                        <a href="myevent.php">RSVP</a>
                    <?php endif; ?>
                    <a href="login1.php">Logout</a>
                </div>
            </li> 
        </ul>
    </nav>
</section>

<section class="add-event">
    <h2>Tambah Event Baru</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="form_action" value="insert_event">
        <label>Nama Event:</label>
        <input type="text" name="event_name" required>
        <label>Tanggal:</label>
        <input type="date" name="event_date" required>
        <label>Jam Mulai:</label>
        <input type="time" name="start_time">
        <label>Jam Selesai:</label>
        <input type="time" name="end_time">
        <label>Lokasi:</label>
        <input type="text" name="location" required>
        <label>Deskripsi:</label>
        <textarea name="description" required></textarea>
        <label>Gambar:</label>
        <input type="file" name="event_image" accept="image/*">
        <button type="submit">Tambah</button>
    </form>
</section>

<section class="event-list">
    <h2>Daftar Event</h2>
     <div class="row">          
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            
    <div class="event-card">
        <h3><?= htmlspecialchars($row['name']); ?></h3>
        <?php if ($row['image']): ?>
            <img src="<?= htmlspecialchars($row['image']); ?>" alt="Event Image">
        <?php endif; ?>
        <p>Tanggal: <?= htmlspecialchars($row['date']); ?></p>
        <p>Waktu: <?= htmlspecialchars($row['start_time'] . ' - ' . $row['end_time']); ?></p>
        <p>Lokasi: <?= htmlspecialchars($row['location']); ?></p>
        <p>Deskripsi: <?= htmlspecialchars($row['description']); ?></p>
        
        <form action="" method="POST">
            <input type="hidden" name="form_action" value="delete_event">
            <input type="hidden" name="delete_event_id" value="<?= $row['event_id']; ?>">
            <input type="hidden" name="delete_event_image" value="<?= htmlspecialchars($row['image']); ?>">
            <button type="submit" class="delete-btn">Hapus</button>
        </form>
        <button class="edit-btn" 
            data-id="<?= $row['event_id']; ?>"
            data-name="<?= htmlspecialchars($row['name']); ?>"
            data-date="<?= $row['date']; ?>"
            data-start="<?= $row['start_time']; ?>"
            data-end="<?= $row['end_time']; ?>"
            data-location="<?= htmlspecialchars($row['location']); ?>"
            data-description="<?= htmlspecialchars($row['description']); ?>"
            data-image="<?= htmlspecialchars($row['image']); ?>">
            Edit
        </button>
    </div>


        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada event.</p>
    <?php endif; ?> 
    </div>        
</section>

<div id="confirmDeleteModal" class="modal">
    <div class="modal-content">
        <span class="close-delete-modal">&times;</span>
        <h2>Konfirmasi Hapus</h2>
        <p>Apakah Anda yakin ingin menghapus event <strong id="deleteEventName"></strong>?</p>
        <button id="confirmDeleteButton" class="delete-btn">Ya, Hapus</button>
        <button id="cancelDeleteButton" class="cancel-btn">Batal</button>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Event</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="form_action" value="update_event">
            <input type="hidden" id="edit_event_id" name="event_id">
            <input type="hidden" id="old_image" name="old_image">
            <label for="edit_event_image">Ubah Gambar (Opsional):</label>
            <input type="file" id="edit_event_image" name="edit_event_image" accept="image/*">
            <label for="edit_event_name">Nama Event:</label>
            <input type="text" id="edit_event_name" name="event_name" required>
            <label for="edit_event_date">Tanggal Event:</label>
            <input type="date" id="edit_event_date" name="event_date" required>
            <div class="flex-container">
                <div>
                    <label for="edit_start_time">Jam Mulai:</label>
                    <input type="time" id="edit_start_time" name="start_time" required>
                </div>
                <div>
                    <label for="edit_end_time">Jam Akhir:</label>
                    <input type="time" id="edit_end_time" name="end_time" required>
                </div>
            </div>
            <label for="edit_location">Lokasi:</label>
            <input type="text" id="edit_location" name="location" required>
            <label for="edit_description">Deskripsi:</label>
            <textarea id="edit_description" name="description" rows="4" required></textarea>
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    $("#editModal, #confirmDeleteModal").hide();
    $(".edit-btn").off().on("click", function () {
        closeAllModals();
        $("#edit_event_id").val($(this).data("id"));
        $("#edit_event_name").val($(this).data("name"));
        $("#edit_event_date").val($(this).data("date"));
        $("#edit_start_time").val($(this).data("start"));
        $("#edit_end_time").val($(this).data("end"));
        $("#edit_location").val($(this).data("location"));
        $("#edit_description").val($(this).data("description"));
        $("#old_image").val($(this).data("image"));

        let imageUrl = $(this).data("image");
        if (imageUrl) {
            $("#current_image").attr("src", imageUrl).show();
        } else {
            $("#current_image").hide();
        }

        $("#editModal").fadeIn(); 
    });


    $(".close, #editModal").off().on("click", function (event) {
        if ($(event.target).is("#editModal") || $(event.target).is(".close")) {
            closeEditModal();
        }
    });

    function closeEditModal() {
        $("#editModal").fadeOut();
        setTimeout(() => {
            $("#edit_event_id").val("");
            $("#edit_event_name").val("");
            $("#edit_event_date").val("");
            $("#edit_start_time").val("");
            $("#edit_end_time").val("");
            $("#edit_location").val("");
            $("#edit_description").val("");
            $("#old_image").val("");
            $("#edit_event_image").val("");
            $("#current_image").attr("src", "").hide();
        }, 300);
    }

    function closeAllModals() {
        $("#editModal, #confirmDeleteModal").fadeOut();
    }

    $(".delete-btn").off().on("click", function (event) {
        event.preventDefault(); 
        let eventId = $(this).closest("form").find("input[name='delete_event_id']").val();
        let eventName = $(this).closest(".event-card").find("h3").text();

        $("#confirmDeleteModal").fadeIn(); 
        $("#deleteEventName").text(eventName); /
        $("#confirmDeleteButton").data("event-id", eventId); 
    });

    $("#confirmDeleteButton").off().on("click", function () {
        let eventId = $(this).data("event-id");
        let form = $("form input[name='delete_event_id'][value='" + eventId + "']").closest("form");

        $("#confirmDeleteModal").fadeOut();
        setTimeout(() => {
            form.submit();
        }, 300);
    });

    $("#cancelDeleteButton, .close-delete-modal").off().on("click", function () {
        $("#confirmDeleteModal").fadeOut();
    });


    function showNotification(message) {
        closeEditModal();
        alert(message);
        $("#confirmDeleteModal").fadeOut();
    }

    <?php if ($eventUpdated !== null): ?>
        showNotification("<?= $eventUpdated ? '✅ Event berhasil diperbarui!' : '❌ Gagal memperbarui event.'; ?>");
    <?php endif; ?>

    <?php if ($eventAdded !== null): ?>
        showNotification("<?= $eventAdded ? '🎉 Event berhasil ditambahkan!' : '❌ Gagal menambahkan event.'; ?>");
    <?php endif; ?>

    <?php if ($eventDeleted !== null): ?>
        showNotification("<?= $eventDeleted ? '🗑️ Event berhasil dihapus!' : '❌ Gagal menghapus event.'; ?>");
    <?php endif; ?>
});

</script>

</body>
</html>
