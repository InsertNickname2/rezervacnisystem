<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurantdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Připojení selhalo: " . $conn->connect_error);
}

// ✅ Přihlášení (prioritně admin)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    // ✅ Přihlášení admina (pevné přihlašovací údaje)
    if ($email === 'admin' && $password === 'admin') {
        $_SESSION['role'] = 'admin';
        echo "admin"; // ✅ Vrátí admin -> JS přesměruje na admin_dashboard.php
        exit;
    }

    // Kontrola, zda nejsou pole prázdná
    if (empty($email) || empty($password)) {
        echo "Vyplňte všechna pole!";
        exit;
    }

    // Přihlášení jako uživatel
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = 'user';
                $_SESSION['email'] = $email;
                echo "user"; // ✅ Vrátí user -> JS přesměruje na user_dashboard.php
                exit;
            } else {
                echo "Neplatný e-mail nebo heslo.";
            }
        } else {
            echo "Neplatný e-mail nebo heslo.";
        }
    } else {
        echo "Chyba při zpracování přihlášení: " . $stmt->error;
    }

    $stmt->close();
}

// ✅ Registrace
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    // Kontrola, zda nejsou pole prázdná
    if (empty($email) || empty($password)) {
        echo "Vyplňte všechna pole!";
        exit;
    }

    // Kontrola, jestli už e-mail existuje
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Tento e-mail je již zaregistrovaný.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registrace byla úspěšná!";
        } else {
            echo "Chyba: " . $stmt->error;
        }
    }

    $stmt->close();
}

// ✅ Rezervace
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reserve"])) {
    $name = $_POST["name"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $email = $_POST["email"] ?? '';
    $reservation_date = $_POST["reservation_date"] ?? '';
    $guests = $_POST["guests"] ?? '';
    $notes = $_POST["notes"] ?? '';

    if (empty($name) || empty($phone) || empty($email) || empty($reservation_date) || empty($guests)) {
        echo "Vyplňte všechna povinná pole!";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO reservations (name, phone, email, reservation_date, guests, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $name, $phone, $email, $reservation_date, $guests, $notes);

    if ($stmt->execute()) {
        echo "Rezervace byla úspěšně odeslána!";
    } else {
        echo "Chyba při odesílání rezervace: " . $stmt->error;
    }
    $stmt->close();
}