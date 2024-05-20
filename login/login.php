<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /");
} else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
        or die('Could not connect: ' . pg_last_error());
}
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
    <?php
    if ($dbconn) {
        $loggato = 0;
        $email = $_POST['inputEmail'];
        $url = "../logineffettuato.php?email=" . urlencode($email);
        $q1 = "SELECT * FROM utente WHERE email=$1";
        $result = pg_query_params($dbconn, $q1, array($email));
        if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $password = $_POST['inputPassword'];
            $q2 = "SELECT * FROM utente WHERE email=$1 and pwd=$2";
            $data = pg_query_params($dbconn, $q2, array($email, $password));
            if ($tuple = pg_fetch_array($data, null, PGSQL_ASSOC)) {
                $_SESSION['loggato'] = true; // Imposta il valore di 'loggato' nella sessione
                $_SESSION['email'] = $_POST['inputEmail']; // Memorizza l'email dell'utente nella sessione
                header("Location: ../index.php"); // Reindirizza alla homepage
                //exit();
            }
            else {
                echo "<script>
                    alert('Password errata. Clicca OK per riprovare.');
                    window.location.href = '../login/index.html';
                </script>";
            }
        } else {
            echo "<script>
                alert('Spiacente, l'indirizzo email non è registrato. Clicca OK per registrarti.');
                window.location.href = '../registrazione/index.html';
            </script>";
        }
    }
    ?>
</body>
</html>
