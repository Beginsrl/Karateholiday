<?php
// admin/dashboard.php
session_start();

// Simple password protection (CHANGE THIS!)
$PASSWORD = 'admin123';

if (isset($_POST['login'])) {
    if ($_POST['password'] === $PASSWORD) {
        $_SESSION['logged_in'] = true;
    } else {
        $error = "Password errata";
    }
}

if (!isset($_SESSION['logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Admin Login</title>
    </head>

    <body style="font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh;">
        <form method="post" style="padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
            <h2>Login Admin</h2>
            <?php if (isset($error))
                echo "<p style='color:red'>$error</p>"; ?>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Entra</button>
        </form>
    </body>

    </html>
    <?php
    exit;
}

// Logged in: Show Data
// Logged in: Show Data
$dbFile = __DIR__ . '/../registrations.db';
$db = new SQLite3($dbFile);
$results = $db->query("SELECT * FROM registrations ORDER BY data_registrazione DESC");
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Karate Holiday</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .receipt-link {
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Iscrizioni Ricevute</h1>
    <p>Benvenuto Admin. <a href="?logout">Logout</a></p>

    <?php
    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: dashboard.php");
        exit;
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Email</th>
                <th>Tipologia</th>
                <th>Atleta Accompagnato</th>
                <th>Soggiorno</th>
                <th>Ricevuta</th>
                <th>Dettagli</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $results->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td>
                        <?= $row['id'] ?>
                    </td>
                    <td>
                        <?= $row['data_registrazione'] ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['nome']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['cognome']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['email']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['tipologia']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['atleta_accompagnato'] ?? '-') ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['soggiorno']) ?>
                    </td>
                    <td>
                        <?php if ($row['ricevuta_path']): ?>
                            <a href="../../<?= htmlspecialchars($row['ricevuta_path']) ?>" target="_blank"
                                class="receipt-link">Vedi File</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <details>
                            <summary>Espandi</summary>
                            <ul style="font-size: 0.9em;">
                                <li><strong>CF:</strong>
                                    <?= htmlspecialchars($row['codice_fiscale']) ?>
                                </li>
                                <li><strong>Nascita:</strong>
                                    <?= htmlspecialchars($row['data_nascita']) ?> (
                                    <?= htmlspecialchars($row['luogo_nascita']) ?>)
                                </li>
                                <li><strong>Indirizzo:</strong>
                                    <?= htmlspecialchars($row['indirizzo']) ?>
                                </li>
                                <li><strong>Tel:</strong>
                                    <?= htmlspecialchars($row['telefono'] ?? 'N/A') ?>
                                </li>
                                <li><strong>Spec:</strong>
                                    <?= htmlspecialchars($row['specialita']) ?>
                                </li>
                                <li><strong>Allergie:</strong>
                                    <?= htmlspecialchars($row['allergie']) ?>
                                </li>
                                <li><strong>Peso/Alt:</strong>
                                    <?= htmlspecialchars($row['peso_altezza']) ?>
                                </li>
                                <li><strong>Assoc:</strong>
                                    <?= htmlspecialchars($row['associazione']) ?>
                                </li>
                            </ul>
                        </details>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>