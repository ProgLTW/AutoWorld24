<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9") or die('Could not connect: ' . pg_last_error());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['preferito'])) {
    $annuncioId = $_POST['id'];
    $preferito = $_POST['preferito'];

    $query = "UPDATE annuncio SET preferito = $preferito WHERE id = $annuncioId";
    $result = pg_query($dbconn, $query);

    if ($result) {
        echo "Aggiornamento del preferito avvenuto con successo!";
    } else {
        echo "Errore durante l'aggiornamento del preferito: " . pg_last_error($dbconn);
    }
} else {
    echo "Metodo non consentito o parametri mancanti!";
}

pg_close($dbconn);
?>
