<?php
session_start();
$dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9") or die('Could not connect: ' . pg_last_error());
if ($dbconn) {
    $annuncioId = isset($_POST['id']) ? $_POST['id'] : null;
    $nascosto = isset($_POST['nascosto']) ? $_POST['nascosto'] : null;
    if ($annuncioId) {
        if ($nascosto == 't') {
            $query = "UPDATE annuncio SET nascosto = true WHERE id = $1";
            $result = pg_query_params($dbconn, $query, array($annuncioId));
            if ($result) {
                echo "L'annuncio è stato contrassegnato come venduto con successo.";
            } else {
                echo "Errore durante l'aggiornamento dello stato dell'annuncio: " . pg_last_error($dbconn);
            }
        }
        else {
            $query = "UPDATE annuncio SET nascosto = false WHERE id = $1";
            $result = pg_query_params($dbconn, $query, array($annuncioId));
            if ($result) {
                echo "L'annuncio è di nuovo visibile.";
            } else {
                echo "Errore durante l'aggiornamento dello stato dell'annuncio: " . pg_last_error($dbconn);
            }
        }
    } else {
        echo "ID dell'annuncio non valido.";
    }
    pg_close($dbconn);
} else {
    echo "Connessione al database non riuscita.";
}
?>
