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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Registration Form</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->

<body>
    <img src="bg.avif" alt="Keshav Mahavidyalaya" class="bg">
    <div class="container">
        <h1>Welcome to Trip Registration Form</h1>
        <p>We are excited to have you here! Please fill out the form below to register yourself for this Trip.</p>

        <?php if ($show_thanks): ?>
            <p class='submitMsg'>
                Thanks for submitting your form. We are happy to see you joining the trip...
            </p>
        <?php endif; ?>

        <form action="index.php" method="post">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" placeholder="Enter your name here..." required><br><br>

            <label for="rollno">College RollNo:</label><br>
            <input type="number" id="rollno" name="rollno" placeholder="Enter your Roll Number here..." required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" placeholder="Enter your email here..." required><br><br>

            <label for="phone">Phone Number:</label><br>
            <input type="phone" id="phone" name="phone" placeholder="Enter your phone number here.." required><br><br>

            <label for="message">Message:</label><br>
            <textarea id="message" name="message" rows="4" cols="50" placeholder="Enter your message here..."></textarea><br><br>


            <button class="btn" type="submit">Submit</button>
        </form>
        <p class="footer">I am Ankush Kumar, a student of Keshav Mahavidyalaya, University of Delhi, who have created this travel form as part of my project. I hope you find it useful!</p>
    </div>

    <style>
        /* =============================================================
   Global reset
   =========================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* =============================================================
   Background image (kept exactly like your original)
   =========================================================== */
        .bg {
            width: 100%;
            position: absolute;
            z-index: -1;
            opacity: 0.5;
            /* keep the content visible on top */
            animation: bgZoom 15s ease-in-out infinite alternate;
            /* subtle pan/zoom */
        }

        @keyframes bgZoom {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(1.08);
            }
        }

        /* =============================================================
   Container – unchanged layout, centred on page
   =========================================================== */
        .container {
            max-width: 80%;
            margin: auto;
            padding: 35px;
            border-radius: 20px;
            animation: fadeIn 1s ease-out;
            /* gentle fade‑in */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =============================================================
   Heading – rainbow gradient + sweeping light, now perfectly centred
   =========================================================== */
        .container h1 {
            text-align: center;
            font-family: "Arial", sans-serif;
            font-size: 33px;
            font-weight: 650;
            letter-spacing: 1px;
            position: relative;
            display: block;
            /* block so margin:auto centres it */
            width: fit-content;
            margin: 0.5rem auto;
            /* centre horizontally */

            /* animated rainbow */
            background: linear-gradient(90deg,
                    #ff0057 0%,
                    #ff8c00 16%,
                    #ffd500 33%,
                    #1aff00 50%,
                    #00c2ff 66%,
                    #7a00ff 83%,
                    #ff0057 100%);
            background-size: 400% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: rainbowShift 8s linear infinite;
            overflow: hidden;
            /* keep sheen inside */
        }

        @keyframes rainbowShift {
            0% {
                background-position: 0% 0;
            }

            100% {
                background-position: 400% 0;
            }
        }

        /* sweeping light */
        .container h1::before {
            content: attr(data-text);
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg,
                    transparent 0%,
                    rgba(255, 255, 255, 0.9) 50%,
                    transparent 100%);
            background-size: 50% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            transform: translateX(-150%);
            animation: sheen 3s ease-in-out infinite;
        }

        @keyframes sheen {
            0% {
                transform: translateX(-150%);
            }

            60% {
                transform: translateX(150%);
            }

            100% {
                transform: translateX(150%);
            }
        }

        /* =============================================================
   Labels – colour pop on hover, unchanged positioning
   =========================================================== */
        label {
            font-family: "Arial", sans-serif;
            font-size: 15px;
            margin: -20px 0;
            display: block;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        label:hover {
            color: #ff0057;
        }

        p {
            font-family: "Arial", sans-serif;
            font-size: 17px;
            text-align: center;
        }

        /* =============================================================
   Inputs & textarea – highlight on focus, unchanged size
   =========================================================== */
        input,
        textarea {
            font-family: "Arial", sans-serif;
            outline: none;
            width: 65%;
            padding: 7px;
            margin: 11px auto;
            border: 2px solid #000000;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus,
        textarea:focus {
            border-color: #00c2ff;
            box-shadow: 0 0 10px rgba(0, 194, 255, 0.5);
        }

        /* =============================================================
   Form layout – unchanged
   =========================================================== */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-top: 50px;
        }

        /* =============================================================
   Button – pulse + glow on hover
   =========================================================== */
        .btn {
            padding: 10px;
            margin: 11px auto;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #8600ff, #d400ff);
            color: white;
            font-size: 15px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            animation: pulse 2.5s ease-in-out infinite;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 12px rgba(212, 0, 255, 0.7);
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 rgba(134, 0, 255, 0.7);
            }

            50% {
                box-shadow: 0 0 15px rgba(134, 0, 255, 0.9);
            }
        }

        /* =============================================================
   Footer & submit message – subtle fade in
   =========================================================== */
        .footer {
            text-align: center;
            font-family: "Arial", sans-serif;
            font-size: 15px;
            padding: 20px;
            font-weight: bold;
            color: green;
            animation: fadeIn 1.5s ease-out 0.5s both;
        }

        .submitMsg {
            color: green;
            animation: fadeIn 1s ease-out both;
            font-weight: bold;
        }
    </style>

    <script src="index.js"></script>
</body>

</html>