<?php
// Mulai sesi PHP
session_start();

// Include koneksi database
include 'connection.php';

// Periksa apakah formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // Validasi data
    if ($password !== $confirm_password) {
        $error_message = "Password dan Konfirmasi Password tidak cocok!";
    } else {
        // Tentukan role berdasarkan username
        $role = ($username === "admin1") ? "admin" : "user";

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk memeriksa apakah username atau email sudah ada
        $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Jika username atau email sudah ada, tampilkan pesan error
            $error_message = "Username atau Email sudah terdaftar!";
        } else {
            // Query untuk memasukkan data ke tabel users
            $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

            // Eksekusi query
            if ($stmt->execute()) {
                // Simpan data sesi
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Arahkan pengguna ke halaman yang sesuai
                if ($role === "admin") {
                    header("Location: indexadmin.php"); // Halaman untuk admin
                } else {
                    header("Location: index.php"); // Halaman untuk user biasa
                }
                exit();
            } else {
                $error_message = "Gagal menyimpan data: " . $stmt->error;
            }

            $stmt->close();
        }

        $stmt_check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="./style/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Mansalva&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="welcome-text">
        <h1>Welcome!</h1>
        <h1 class="subtext">To Winzone.</h1>
</div>

<section class="register">
  <div class="container">
    <div class="wrapper"> 
        <form action="" method="POST">
            <h1>Register</h1>
            <?php if (!empty($error_message)) : ?>
                <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <div class="input-box">
                <label for="username">Username</label>
                <input type="text" placeholder="Username" name="username" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Masukkan email">
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password">
            </div>
            <div class="input-box">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Masukkan ulang password">
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox"> Remember me</label>
            </div>

            <button type="submit" class="btn">Register</button>

            <div class="register-link">
                <p>Already have an account? <a href="login1.php">Login</a></p>
            </div>
        </form>
    </div>
</div>

</body>
</html>
