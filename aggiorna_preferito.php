<?php 
session_start();
$dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9") or die('Could not connect: ' . pg_last_error());
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['checked'])) {
    $annuncioId = $_POST['id'];
    $isChecked = filter_var($_POST['checked'], FILTER_VALIDATE_BOOLEAN);
    echo "isChecked: " . ($isChecked ? "true" : "false") . "<br>";
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        if ($isChecked) {
            echo "Query per l'aggiunta: UPDATE utente SET preferiti = array_append(preferiti, $annuncioId) WHERE email = $email<br>";
            $query = "UPDATE utente SET preferiti = array_append(preferiti, $1) WHERE email = $2";
        } else {
            echo "Query per la rimozione: UPDATE utente SET preferiti = array_remove(preferiti, $annuncioId) WHERE email = $email<br>";
            $query = "UPDATE utente SET preferiti = array_remove(preferiti, $1) WHERE email = $2";
        }
        $result = pg_query_params($dbconn, $query, array($annuncioId, $email));
        if ($result) {
            echo "Aggiornamento del preferito avvenuto con successo!";
        } else {
            echo "Errore durante l'aggiornamento del preferito: " . pg_last_error($dbconn);
        }
    } else {
        echo "Utente non loggato!";
    }
} else {
    echo "Metodo non consentito o parametri mancanti!";
}
pg_close($dbconn);
?>
