<?php
// submit-registration.php

// Create/Connect to SQLite database
// Create/Connect to SQLite database
$dbFile = __DIR__ . '/../registrations.db';
$db = new SQLite3($dbFile);

// Create table if not exists
$query = "CREATE TABLE IF NOT EXISTS registrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    soggiorno TEXT,
    tipologia TEXT,
    email TEXT,
    cognome TEXT,
    nome TEXT,
    codice_fiscale TEXT,
    data_nascita TEXT,
    luogo_nascita TEXT,
    indirizzo TEXT,
    specialita TEXT,
    allergie TEXT,
    peso_altezza TEXT,
    associazione TEXT,
    compagni_stanza TEXT,
    animale TEXT,
    ricevuta_path TEXT,
    data_registrazione DATETIME DEFAULT CURRENT_TIMESTAMP
)";
$db->exec($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $files = $_FILES;

    // Handle file upload
    // Handle file upload
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ricevutaPath = '';
    if (isset($files['ricevuta']) && $files['ricevuta']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($files['ricevuta']['name']);
        $targetPath = $uploadDir . uniqid() . '_' . $fileName;
        if (move_uploaded_file($files['ricevuta']['tmp_name'], $targetPath)) {
            $ricevutaPath = str_replace(__DIR__ . '/../', '', $targetPath); // Save relative path
        }
    }

    // Prepare Insert Statement
    $stmt = $db->prepare("INSERT INTO registrations (
        soggiorno, tipologia, email, cognome, nome, codice_fiscale, 
        data_nascita, luogo_nascita, indirizzo, specialita, allergie, 
        peso_altezza, associazione, compagni_stanza, animale, ricevuta_path
    ) VALUES (
        :soggiorno, :tipologia, :email, :cognome, :nome, :codice_fiscale, 
        :data_nascita, :luogo_nascita, :indirizzo, :specialita, :allergie, 
        :peso_altezza, :associazione, :compagni_stanza, :animale, :ricevuta_path
    )");

    $stmt->bindValue(':soggiorno', $data['soggiorno'] ?? '');
    $stmt->bindValue(':tipologia', $data['tipologia'] ?? '');
    $stmt->bindValue(':email', $data['email'] ?? '');
    $stmt->bindValue(':cognome', $data['cognome'] ?? '');
    $stmt->bindValue(':nome', $data['nome'] ?? '');
    $stmt->bindValue(':codice_fiscale', $data['codice'] ?? ''); // Form field might be 'codice' or 'codice_fiscale' - checking needed
    $stmt->bindValue(':data_nascita', $data['nascita'] ?? '');
    $stmt->bindValue(':luogo_nascita', $data['luogo'] ?? '');
    $stmt->bindValue(':indirizzo', $data['indirizzo'] ?? '');
    $stmt->bindValue(':specialita', $data['specialita'] ?? '');
    $stmt->bindValue(':allergie', $data['allergie'] ?? '');
    $stmt->bindValue(':peso_altezza', $data['peso'] ?? '');
    $stmt->bindValue(':associazione', $data['associazione'] ?? '');
    $stmt->bindValue(':compagni_stanza', $data['compagni'] ?? '');
    $stmt->bindValue(':animale', $data['animale'] ?? '');
    $stmt->bindValue(':ricevuta_path', $ricevutaPath);

    if ($stmt->execute()) {
        // Send Email using PHP mail() - Simple version
        // In production consider using PHPMailer or similar libraries
        $to = $data['email'] ?? '';
        $subject = 'Conferma Iscrizione - Karate Holiday 2026';
        $message = "Ciao {$data['nome']} {$data['cognome']},\n\nLa tua iscrizione è stata ricevuta con successo.\nTi aspettiamo!";
        $headers = 'From: info@karateholiday.it' . "\r\n" .
            'Reply-To: info@karateholiday.it' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        // Uncomment to send real email
        // mail($to, $subject, $message, $headers);

        // Notify manager
        // mail('manager@karateholiday.it', 'Nuova Iscrizione', print_r($data, true), $headers);

        // Redirect with success
        header('Location: /?success=true#registration-form');
        exit;
    } else {
        header('Location: /?error=true#registration-form');
        exit;
    }
} else {
    http_response_code(405);
    echo "Method Not Allowed";
}
?>