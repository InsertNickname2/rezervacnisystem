<?php
session_start();

require 'database.php'; // Použije se $conn z database.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset(); // Zruší všechny proměnné relace
    session_destroy(); // Zničí relaci
    header('Location: form.html');
    exit;
}

// Načtení rezervací z databáze
$sql = "SELECT * FROM reservations ORDER BY reservation_date DESC";
$result = $conn->query($sql);

$reservations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Správa rezervací</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .logout-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50; /* Zelené pozadí */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .logout-link:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>SPRÁVA REZERVACÍ</h1>
    <form id="logoutForm" method="POST" style="display: none;">
        <input type="hidden" name="logout" value="1">
    </form>

    <!-- Odkaz na odhlášení -->
    <a href="#" class="logout-link" onclick="document.getElementById('logoutForm').submit(); return false;">Odhlásit se</a>


    <br>
    <br>

    <table>
        <thead>
            <tr>
                <th>Jméno</th>
                <th>Telefon</th>
                <th>Email</th>
                <th>Datum a čas</th>
                <th>Počet osob</th>
                <th>Poznámky</th>
                <th>Akce</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['phone']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['guests']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['notes']); ?></td>
                    <td>
                        <form action="edit_reservation.php" method="GET" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">
                            <button type="submit">Upravit</button>
                        </form>
                        <form action="delete_reservation.php" method="POST" style="display: inline;" onsubmit="return confirm('Opravdu chcete tuto rezervaci odstranit?');">
                            <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">
                            <button type="submit">Odstranit</button>
                        </form>
                    </td>
                </tr>   
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
