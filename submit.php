<?php
session_start();            // ← add this as the very first line

$server   = "localhost";
$username = "root";
$password = "";
$dbname   = "trip";
$port     = 3307;

$conn = mysqli_connect($server, $username, $password, $dbname, $port);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/* ---------- handle the POST ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name    = mysqli_real_escape_string($conn, $_POST['name']    ?? '');
    $rollno  = mysqli_real_escape_string($conn, $_POST['rollno']  ?? '');
    $email   = mysqli_real_escape_string($conn, $_POST['email']   ?? '');
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']   ?? '');
    $message = mysqli_real_escape_string($conn, $_POST['message'] ?? '');

    $sql = "INSERT INTO `trip` (`Name`, `RollNo`, `Email`, `Phone`, `other`, `dt`)
            VALUES ('$name', '$rollno', '$email', '$phone', '$message', CURRENT_TIMESTAMP())";
    $conn->query($sql);
    $conn->close();

    /* flash‑flag for one‑time message */
    $_SESSION['just_submitted'] = true;

    /* PRG redirect (avoids duplicate insert on refresh) */
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* pull & clear the flag so it only lives one request */
$show_thanks = !empty($_SESSION['just_submitted']);
unset($_SESSION['just_submitted']);
?>
header("Location: https://your-netlify-site.netlify.app?ok=1");
exit;
