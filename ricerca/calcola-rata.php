<?php
// Funzione per calcolare i dettagli del prestito
function calcolaDettagliPrestito($importo_prestito, $tasso_interesse, $durata_prestito) {
    // Inizializza un array per i risultati
    $risultati = array();

    // Calcola i dettagli del prestito
    $tot_prestito = $importo_prestito;
    $tot_interessi = $importo_prestito * ($tasso_interesse / 100);
    $num_rate = $durata_prestito * 12;
    $rata_mese = ($tot_interessi + $tot_prestito) / $num_rate;

    // Aggiungi i risultati all'array
    $risultati['tot_prestito'] = $tot_prestito;
    $risultati['tot_interessi'] = $tot_interessi;
    $risultati['num_rate'] = $num_rate;
    $risultati['rata_mese'] = $rata_mese;

    return $risultati;
}

// Funzione per controllare se i campi del form sono stati compilati correttamente
function controllaCampiForm($importo_prestito, $tasso_interesse, $durata_prestito) {
    // Verifica che tutti i campi siano stati compilati
    if (!empty($importo_prestito) && !empty($tasso_interesse) && !empty($durata_prestito)) {
        // Verifica che i valori siano numerici e positivi
        if (is_numeric($importo_prestito) && is_numeric($tasso_interesse) && is_numeric($durata_prestito) && $importo_prestito > 0 && $tasso_interesse > 0 && $durata_prestito > 0) {
            return true; // I valori sono validi
        } else {
            return false; // I valori non sono validi
        }
    } else {
        return false; // Non tutti i campi sono stati compilati
    }
}

// Esempio di utilizzo delle funzioni
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i valori inviati dal form
    $importo_prestito = $_POST['importo_prestito'];
    $tasso_interesse = $_POST['tasso_interesse'];
    $durata_prestito = $_POST['durata_prestito'];

    // Controlla se i campi del form sono stati compilati correttamente
    if (controllaCampiForm($importo_prestito, $tasso_interesse, $durata_prestito)) {
        // Calcola i dettagli del prestito
        $dettagli_prestito = calcolaDettagliPrestito($importo_prestito, $tasso_interesse, $durata_prestito);
        
        // Esempio di output dei risultati
        echo "Importo totale prestito: " . $dettagli_prestito['tot_prestito'] . "<br>";
        echo "Totale interessi: " . $dettagli_prestito['tot_interessi'] . "<br>";
        echo "Numero di rate: " . $dettagli_prestito['num_rate'] . "<br>";
        echo "Rata mensile: " . $dettagli_prestito['rata_mese'] . "<br>";
    } else {
        // Messaggio di errore se i valori non sono validi
        echo "I valori inseriti non sono validi. Assicurati di inserire valori numerici e positivi per l'importo del prestito, il tasso di interesse e la durata del prestito.";
    }
}
?>
