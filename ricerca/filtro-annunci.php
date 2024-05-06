<?php
// Connessione al database
$dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
    or die('Could not connect: ' . pg_last_error());

// Verifica se la connessione è stata stabilita correttamente
if ($dbconn) {
    // Recupera i dati dei filtri inviati dal client
    $marca = isset($_POST['marca']) ? pg_escape_string($_POST['marca']) : null;
    $prezzoMassimo = isset($_POST['prezzoMassimo']) ? intval($_POST['prezzoMassimo']) : null;
    $anno = isset($_POST['anno']) ? intval($_POST['anno']) : null;
    $chilometraggioMassimo = isset($_POST['chilometraggioMassimo']) ? intval($_POST['chilometraggioMassimo']) : null;

    // Costruisci la query per filtrare gli annunci
    $query = "SELECT * FROM annuncio WHERE 1=1"; // Inizia con una condizione sempre vera

    // Aggiungi le condizioni dei filtri, se presenti
    if ($marca) {
        $query .= " AND marca = '$marca'";
    }
    if ($prezzoMassimo) {
        $query .= " AND prezzo <= $prezzoMassimo";
    }
    if ($anno) {
        $query .= " AND anno = $anno";
    }
    if ($chilometraggioMassimo) {
        $query .= " AND chilometraggio <= $chilometraggioMassimo";
    }
    // Aggiungi altre condizioni per gli altri filtri...

    // Esegui la query
    $result = pg_query($dbconn, $query);

    if ($result) {
        // Inizializza una variabile per memorizzare l'output HTML degli annunci
        $output = '';

        // Itera sui risultati della query per costruire l'HTML degli annunci
        while ($row = pg_fetch_assoc($result)) {
            // Costruisci l'HTML per ciascun annuncio (come fatto nel tuo codice PHP originale)
            $output .= "<div class='container3'>";
            // Aggiungi le informazioni dell'annuncio...
            $output .= "</div>";
        }

        // Restituisci l'HTML degli annunci filtrati al client
        echo $output;
    } else {
        // Gestisci eventuali errori nella query
        echo "Errore durante l'esecuzione della query: " . pg_last_error($dbconn);
    }

    // Rilascia la risorsa del risultato
    pg_free_result($result);
} else {
    // Gestisci eventuali errori nella connessione al database
    echo "Connessione al database non riuscita.";
}

// Chiudi la connessione al database
pg_close($dbconn);
?>
