<?php
require 'database.php';
session_start();

// Kontrola, zda je uživatel přihlášen
if (!isset($_SESSION['role'])) {
    header('Location: form.html');
    exit;
}

$user_email = $_SESSION['email'] ?? null; // E-mail přihlášeného uživatele

if (!$user_email) {
    echo "Uživatelský e-mail nebyl nalezen.";
    exit;
}

// Zpracování formuláře po odeslání
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $reservation_date = $_POST["reservation_date"];
    $guests = $_POST["guests"];
    $notes = $_POST["notes"];

    // Aktualizace rezervace na základě e-mailu
    $stmt = $conn->prepare("UPDATE reservations SET name = ?, phone = ?, reservation_date = ?, guests = ?, notes = ? WHERE email = ?");
    $stmt->bind_param("sssiss", $name, $phone, $reservation_date, $guests, $notes, $user_email);

    if ($stmt->execute()) {
        // Po úspěšné úpravě přesměruj na user_dashboard.php
        header("Location: user_dashboard.php");
        exit();
    } else {
        echo "Chyba při aktualizaci rezervace: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Načtení rezervace na základě e-mailu
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();

    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();

    if (!$reservation) {
        die("Rezervace nebyla nalezena.");
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Úprava rezervace</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Úprava rezervace</h1>
    <form method="post">
        <label>Jméno:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($reservation['name']) ?>" required>
        
        <label>Telefon:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($reservation['phone']) ?>" required>
        
        <label>Datum:</label>
        <input type="datetime-local" name="reservation_date" value="<?= htmlspecialchars($reservation['reservation_date']) ?>" required>
        
        <label>Počet osob:</label>
        <input type="number" name="guests" value="<?= htmlspecialchars($reservation['guests']) ?>" required>
        
        <label>Poznámky:</label>
        <textarea name="notes" required><?= htmlspecialchars($reservation['notes']) ?></textarea>
        
        <button type="submit">Uložit změny</button>
    </form>
</body>
</html>