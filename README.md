Popis projektu
Tento projekt představuje rezervační systém pro restauraci, který umožňuje uživatelům provádět rezervace a správu těchto rezervací. Uživatelé se mohou přihlásit, registrovat se a upravovat své rezervace, zatímco administrátor má přístup k administrativnímu rozhraní pro správu celého systému.

Struktura databáze
Tabulka reservations
Tato tabulka uchovává informace o rezervacích uživatelů. Každá rezervace je přiřazena k uživatelskému účtu na základě jeho ID.

Sloupce:
id (INT, AUTO_INCREMENT, PRIMARY KEY): Unikátní identifikátor rezervace.
name (VARCHAR(100)): Jméno osoby, která provedla rezervaci.
phone (VARCHAR(20)): Telefonní číslo osoby, která provedla rezervaci.
email (VARCHAR(100)): E-mailová adresa osoby, která provedla rezervaci.
reservation_date (DATETIME): Datum a čas rezervace.
guests (INT): Počet osob na rezervaci.
notes (TEXT): Poznámky k rezervaci (např. speciální požadavky).
Tabulka users
Tato tabulka uchovává informace o uživatelích systému. Každý uživatel má svůj vlastní účet s přihlašovacími údaji (uživatelské jméno, e-mail a heslo).

Sloupce:
id (INT, AUTO_INCREMENT, PRIMARY KEY): Unikátní identifikátor uživatele.
username (VARCHAR(50)): Uživatelské jméno (unikátní).
email (VARCHAR(100)): E-mailová adresa uživatele (unikátní).
password (VARCHAR(255)): Heslo uživatele (uchováno v zašifrované podobě).

Použití
Přihlášení: Uživatelé se mohou přihlásit pomocí své e-mailové adresy a hesla.
Rezervace: Po přihlášení mohou uživatelé vytvářet a upravovat rezervace.
Administrátor: Administrátor může přistupovat k administrativnímu rozhraní, kde má plnou kontrolu nad rezervacemi a uživatelskými účty.

Bezpečnostní opatření
Hesla uživatelů jsou uložena v databázi v zašifrované podobě.
Doporučujeme implementovat další bezpečnostní opatření, jako je ochrana proti SQL injection, použití HTTPS pro šifrování komunikace a omezení přístupu na základě oprávnění.
