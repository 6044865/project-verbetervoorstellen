<?php
 
require_once 'config.php';
 
$message = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $naam = trim($_POST['naam']);
    $email = trim($_POST['email']);
    $wachtwoord = $_POST['wachtwoord'];
    $wachtwoord_herhaal = $_POST['wachtwoord_herhaal'];
 
    // Check wachtwoorden
    if ($wachtwoord !== $wachtwoord_herhaal) {
 
        $message = '<div class="alert alert-danger">Wachtwoorden komen niet overeen.</div>';
 
    } else {
 
        // Check of email al bestaat
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
 
        if ($check->rowCount() > 0) {
 
            $message = '<div class="alert alert-warning">Dit e-mailadres bestaat al.</div>';
 
        } else {
 
            // Wachtwoord hashen
            $hashedPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);
 
            // Rol is nu "lid"
            $rol = "lid";
 
            // Insert gebruiker
            $stmt = $pdo->prepare("
                INSERT INTO users (naam, email, wachtwoord, rol)
                VALUES (?, ?, ?, ?)
            ");
 
            $success = $stmt->execute([
              
                $naam,
                $email,
                $hashedPassword,
                $rol
            ]);
 
            if ($success) {
                // $message = '<div class="alert alert-success">Registratie succesvol!</div>';
            

                // if ($rol === "admin") {
                //     header("Location: ../admin.php");
                // } else {
                //     header("Location: ../reserveren.html");
                // }
                header("Location: ../reserveren.html");
                exit;
               
            } else {
                $message = '<div class="alert alert-danger">Er ging iets fout.</div>';
            }
        }
    }
}
 
?>
