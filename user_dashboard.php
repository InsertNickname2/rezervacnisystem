<?php
session_start();

// Kontrola, zda je uživatel přihlášen
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: form.html');
    exit;
}

$user_email = $_SESSION['email'] ?? null; // E-mail přihlášeného uživatele

if (!$user_email) {
    echo "Uživatelský e-mail nebyl nalezen.";
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurantdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Připojení selhalo: " . $conn->connect_error);
}

// Získání rezervace na základě e-mailu
$stmt = $conn->prepare("SELECT * FROM reservations WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();

$result = $stmt->get_result();
$reservation = $result->fetch_assoc();

if (!$reservation) {
    echo "Nenalezena žádná rezervace.";
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uživatelské rozhraní</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Uživatelské rozhraní</h1>
    <i>powered by mrkvosoft</i>

    <h2 style="color: white;">Vaše rezervace:</h2>
    <p style="color: white;"><strong>Jméno:</strong> <?= htmlspecialchars($reservation['name']) ?></p>
    <p style="color: white;"><strong>Telefon:</strong> <?= htmlspecialchars($reservation['phone']) ?></p>
    <p style="color: white;"><strong>E-mail:</strong> <?= htmlspecialchars($reservation['email']) ?></p>
    <p style="color: white;"><strong>Datum a čas:</strong> <?= htmlspecialchars($reservation['reservation_date']) ?></p>
    <p style="color: white;"><strong>Počet osob:</strong> <?= htmlspecialchars($reservation['guests']) ?></p>
    <p style="color: white;"><strong>Poznámky:</strong> <?= htmlspecialchars($reservation['notes']) ?></p>

    <!-- Tlačítko pro úpravu rezervace -->
    <form action="edit_reservation.php" method="GET">
        <button type="submit">Upravit rezervaci</button>
    </form>

    <!-- Tlačítko pro smazání rezervace -->
    <form action="delete_reservation.php" method="POST" onsubmit="return confirm('Opravdu chcete smazat rezervaci?')">
        <button type="submit">Smazat rezervaci</button>
    </form>

    <!-- Tlačítko pro odhlášení -->
    <form action="logout.php" method="POST">
        <button type="submit">Odhlásit se</button>
    </form>
</body>
</html>