<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /");
    exit; // Esce dallo script per evitare l'esecuzione di ulteriori istruzioni
} else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
        or die('Could not connect: ' . pg_last_error());
}

if ($dbconn) {
    // Gestione dell'upload delle immagini
    $targetDirectory = "uploads/"; // Directory di destinazione per le immagini
    $uploadedFiles = array(); // Array per memorizzare i percorsi dei file caricati

    // Loop attraverso ogni file caricato
    foreach ($_FILES["foto"]["tmp_name"] as $key => $tmp_name) {
        // Ottieni il nome del file e il percorso temporaneo
        $fileName = $_FILES["foto"]["name"][$key];
        $fileTmpName = $_FILES["foto"]["tmp_name"][$key];
        
        // Crea il percorso di destinazione per il file caricato
        $targetPath = $targetDirectory . basename($fileName);
        
        // Sposta il file dalla directory temporanea alla directory di destinazione
        if (move_uploaded_file($fileTmpName, $targetPath)) {
            // Aggiungi il percorso del file all'array degli upload
            $uploadedFiles[] = $targetPath;
        } else {
            echo "Si è verificato un errore durante il caricamento del file $fileName.<br>";
        }
    }

    // Altri dati dal modulo
    $marca = $_POST['marca'];
    $modello = $_POST['modello'];
    $prezzo = $_POST['prezzo'];
    $trattabile = isset($_POST['trattabile']) ? 'true' : 'false'; // Converte il valore del checkbox in 'true' o 'false'
    $carrozzeria = $_POST['carrozzeria'];
    $anno = $_POST['anno'];
    $chilometraggio = $_POST['chilometraggio'];
    $carburante = $_POST['carburante'];
    $cambio = $_POST['cambio'];
    $potenza = $_POST['potenza'];
    $descrizione = $_POST['descrizione'];

    // Query SQL per l'inserimento dei dati nella tabella Auto
    $query = "INSERT INTO annuncio (marca, modello, prezzo, trattabile, carrozzeria, anno, chilometraggio, carburante, cambio, potenza, foto, descrizione) 
              VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)";
    
    // Esecuzione della query con i parametri
    $result = pg_query_params($dbconn, $query, array($marca, $modello, $prezzo, $trattabile, $carrozzeria, $anno, $chilometraggio, $carburante, $cambio, $potenza, $uploadedFiles, $descrizione));
    
    if ($result) {
        echo "<h1>Dati inseriti correttamente nella tabella Auto</h1>";
    } else {
        echo "<h1>Errore durante l'inserimento dei dati nella tabella Auto</h1>";
    }
}
?>
