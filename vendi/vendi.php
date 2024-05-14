<?php
session_start();
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
    $uploadedFile = ""; // Percorso del file caricato

    // Ottieni il nome del file e il percorso temporaneo del primo file caricato
    $fileName = $_FILES["foto"]["name"];
    $fileTmpName = $_FILES["foto"]["tmp_name"];

    // Crea il percorso di destinazione per il file caricato
    $targetPath = $targetDirectory . basename($fileName);

    // Sposta il file dalla directory temporanea alla directory di destinazione
    if (move_uploaded_file($fileTmpName, $targetPath)) {
        // Aggiungi il percorso del file agli upload
        $uploadedFile = $targetPath;
    } else {
        echo "Si è verificato un errore durante il caricamento del file $fileName.<br>";
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
    $foto = $uploadedFile;
    $descrizione = $_POST['descrizione'];
    $preferito = 0;
    $tipoVeicolo = $_POST['tipoVeicolo'];

    // Ottieni l'email dalla sessione
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

    // Query SQL per l'inserimento dei dati nella tabella Auto
    $query = "INSERT INTO annuncio (marca, modello, prezzo, trattabile, carrozzeria, anno, chilometraggio, carburante, cambio, potenza, foto, descrizione, preferito, email, tipoVeicolo) 
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15)";

    // Esecuzione della query con i parametri
    $result = pg_query_params($dbconn, $query, array($marca, $modello, $prezzo, $trattabile, $carrozzeria, $anno, $chilometraggio, $carburante, $cambio, $potenza, $foto, $descrizione, $preferito, $email, $tipoVeicolo));

        
    if ($result) {
        if ($tipoVeicolo === 'Auto') {
            echo "<script>alert('Dati inseriti correttamente nella tabella Auto');</script>";
        } elseif ($tipoVeicolo === 'Moto') {
            echo "<script>alert('Dati inseriti correttamente nella tabella Moto');</script>";
        }
        echo "<script>window.location.href = '../index.php';</script>";
    } else {
        // Debugging dell'errore
        $error = pg_last_error($dbconn);
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode($error));
        exit;
        //Warning: pg_query_params(): Query failed: ERROR: invalid input syntax for type numeric: "" CONTEXT: unnamed portal parameter $3 = '' in /home/lilith/Linguaggi/AutoWorld24/vendi/vendi.php on line 54
    }
}    
?>
