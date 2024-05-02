<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
    or die('Could not connect: ' . pg_last_error());

if ($dbconn) {
    // Query per recuperare tutti gli annunci dalla tabella annuncio
    $query = "SELECT * FROM annuncio";

    // Esecuzione della query
    $result = pg_query($dbconn, $query);

    if ($result) {
        // Iterazione sui risultati della query per visualizzare gli annunci
        while ($row = pg_fetch_assoc($result)) {
            // Visualizzazione di ciascun annuncio
            echo "<div class='col'>";
            echo "<div class='ad'>";
            echo "<img src='vendi/{$row['foto']}' alt='Foto auto' width='150'>";
            echo "<h2>{$row['marca']} {$row['modello']}</h2>";
            echo "<p>Prezzo: {$row['prezzo']}</p>";
            echo "<p>Trattabile: " . ($row['trattabile'] ? 'Sì' : 'No') . "</p>";
            echo "<p>Carrozzeria: {$row['carrozzeria']}</p>";
            // Aggiungi altri dettagli dell'annuncio qui...
            echo "</div>";
            echo "</div>";
        }
        // Rilascio della risorsa del risultato
        pg_free_result($result);
    } else {
        echo "Errore durante l'esecuzione della query: " . pg_last_error($dbconn);
    }
} else {
    echo "Connessione al database non riuscita.";
}

// Chiusura della connessione al database
pg_close($dbconn);
?>
