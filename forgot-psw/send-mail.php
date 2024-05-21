<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /");
    exit;
} else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
        or die('Could not connect: ' . pg_last_error());
}
if ($dbconn) {
    if (!empty($_POST['email'])) {
        $loggato = 0;
        $email = $_POST['email'];
        $q1 = "SELECT * FROM utente WHERE email=$1";
        $result = pg_query_params($dbconn, $q1, array($email));
        if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $password_temporanea = generateRandomPassword(8);        
        $oggetto = "Password Temporanea";
        $messaggio = "La tua password temporanea è: " . $password_temporanea . "\r\n\r\n";
        $messaggio .= "Ti consigliamo vivamente di cambiare la password al più presto per motivi di sicurezza. Clicca sulla sezione 'Modifica password' nel tuo profilo.";
        $header = "From: ciaoc5500@gmail.com\r\n";
        $header .= "Reply-To: ciaoc5500@gmail.com\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: text/plain; charset=utf-8\r\n";
        $header .= "X-Mailer: PHP/" . phpversion();
        $update_query = "UPDATE utente SET pwd = $1 WHERE email = $2";
        $update_result = pg_query_params($dbconn, $update_query, array($password_temporanea, $email));
        header("Location: ../login/index.html");
        exit();
    } else {
        echo "<script>
            alert('Questa email non esiste nel sito. Clicca OK per riprovare.');
            window.location.href = '../forgot-password/forgot-password.php';
        </script>";
        echo "Questa email non esiste nel sito.";
        }
    }
}
function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomPassword;
}
?>
