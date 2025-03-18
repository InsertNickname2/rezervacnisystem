<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "restaurantdb";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Připojení selhalo: " . $conn->connect_error);
    }

    // Smazání rezervace na základě e-mailu
    $stmt = $conn->prepare("DELETE FROM reservations WHERE email = ?");
    $stmt->bind_param("s", $user_email);

    if ($stmt->execute()) {
        // Po úspěšném smazání přesměruj na form.html
        header("Location: form.html");
        exit();
    } else {
        echo "Chyba při mazání rezervace: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Pokud není odeslán POST požadavek, přesměrujeme uživatele
    header("Location: form.html");
    exit();
}
?>