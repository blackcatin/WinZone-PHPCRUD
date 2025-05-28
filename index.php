<?php
session_start();
include('connection.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}


$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_rsvp'])) {
    $event_id = $_POST['event_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $nama_sekolah = $_POST['nama_sekolah'];


    $sql = "INSERT INTO rsvp (event_id, user_id, name, email, no_telp, nama_sekolah) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $event_id, $user_id, $name, $email, $no_telp, $nama_sekolah);
    $stmt->execute();

    header('Location: rsvp.php');
    exit;
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

      
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

        .lomba .event p strong {
            font-weight: 600;
            color: #3f09ac;
        }

        .rsvp-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50; 
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .rsvp-btn:hover {
            background-color: #45a049;
        }

        /* CSS for RSVP Modal */

        #rsvpModal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* On top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6); 
        }

        
        .modal-content {
            background-color: #fff;
            margin: 10% auto; 
            padding: 20px;
            border-radius: 10px;
            width: 90%; 
            max-width: 500px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.3s ease-in-out;
        }

    
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }


        .modal-content form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .modal-content label {
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }

        .modal-content input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        .modal-content input:focus {
            border-color: #4CAF50; 
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .modal-content button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal-content button:hover {
            background-color: #45a049;
        }

       
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin-top: 15%;
            }
        }

        .footer {
        background-color: #000957;
        color: white;
        min-height: 200px;
        padding: 40px;
        text-align: center;
        margin-top: auto;
        width: 100%;
        border-top-left-radius: 20px; 
        border-top-right-radius: 20px; 
        }

        .footer .container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-direction: column; 
        align-items: center; 
        gap: 20px; 
        }

        .footer .logo {
        font-size: 1.5rem;
        font-weight: bold;
        }

        .footer .links {
        display: flex;
        gap: 15px;
        justify-content: center; 
        }

        .footer .links a {
        color: white;
        text-decoration: none;
        font-size: 1rem;
        }

        .footer .links a:hover {
        text-decoration: underline;
        }

        .footer .socials {
        margin-top: 15px;
        display: flex;
        justify-content: center; 
        }

        .footer .socials a {
        color: white;
        margin: 0 10px;
        font-size: 1.2rem;
        text-decoration: none;
        }

        .footer .socials a:hover {
        color: #8a2be2;
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
                    

                    <button class="rsvp-btn" onclick="openRSVPForm(<?= $row['event_id']; ?>)">RSVP</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada event yang ditambahkan.</p>
        <?php endif; ?>
    </div>
</section>


<div id="rsvpModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRSVPForm()">&times;</span>
        <h2>RSVP Form</h2>
        <form id="rsvpForm" method="POST" action="index.php">
            <input type="hidden" name="event_id" id="event_id">
            <div>
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="no_telp">No. Telepon</label>
                <input type="text" id="no_telp" name="no_telp" required>
            </div>
            <div>
                <label for="nama_sekolah">Nama Sekolah</label>
                <input type="text" id="nama_sekolah" name="nama_sekolah" required>
            </div>
            <button type="submit" name="submit_rsvp">Submit RSVP</button>
        </form>
    </div>
</div>

<script>
   
    function openRSVPForm(eventId) {
        document.getElementById('event_id').value = eventId;
        document.getElementById('rsvpModal').style.display = 'block';
    }

    function closeRSVPForm() {
        document.getElementById('rsvpModal').style.display = 'none';
    }

   
    window.onclick = function(event) {
        if (event.target == document.getElementById('rsvpModal')) {
            closeRSVPForm();
        }
    }
</script>
</body>
</html>