<?php
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
        $email = $_POST['inputEmail'];
        $q1 = "SELECT * FROM utente WHERE email=$1";
        $result = pg_query_params($dbconn, $q1, array($email));
        if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "<script>
                alert('Spiacente, l\\'indirizzo email non è disponibile. Clicca OK per essere reindirizzato alla pagina di login.');
                window.location.href = '../login/index.html';
            </script>";
        } else {
            $nome = $_POST['inputName'];
            $cognome = $_POST['inputSurname'];
            $password = ($_POST['inputPassword']);
            $q2 = "INSERT INTO utente VALUES ($1, $2, $3, $4)";
            $data = pg_query_params($dbconn, $q2, array($email, $password, $nome, $cognome));
            if ($data) {
                echo "<script>
                    alert('Registrazione completata. Puoi iniziare ad usare il sito. Clicca OK per loggarti.');
                    window.location.href = '../login/index.html';
                </script>";
            }
        }
    }
    ?>
</body>
</html>
