<?php
session_start();
$dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9") or die('Could not connect: ' . pg_last_error());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['preferito'])) {
    $annuncioId = $_POST['id'];
    $preferito = $_POST['preferito'];

    // Verifica se l'utente è loggato
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Aggiorna la colonna preferiti dell'utente
        if ($preferito) {
            // Aggiungi l'ID dell'annuncio all'array dei preferiti
            $query = "UPDATE utente SET preferiti = array_append(preferiti, $annuncioId) WHERE email = $1";
        } else {
            // Rimuovi l'ID dell'annuncio dall'array dei preferiti
            $query = "UPDATE utente SET preferiti = array_remove(preferiti, $annuncioId) WHERE email = $1";
        }

        $result = pg_query_params($dbconn, $query, array($email));

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
