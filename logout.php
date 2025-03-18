<?php
session_start();
session_unset(); // Odstraní všechny session proměnné
session_destroy(); // Zničí session
header('Location: form.html'); // Přesměrování na přihlašovací stránku
exit;