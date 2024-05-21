<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /");
    exit;
} else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=utenti user=postgres password=Lukakuinter9")
        or die('Could not connect: ' . pg_last_error());
}
if ($dbconn) {
    $uniqueCode = uniqid();
    $targetDirectory = "uploads/" . (isset($_SESSION['email']) ? $_SESSION['email'] : null) . "/" . $uniqueCode . "/";
    $uploadedFile = "";
    if (!file_exists($targetDirectory)) {
        mkdir($targetDirectory, 0777, true);
    }
    $fileName = $_FILES["foto"]["name"];
    $fileTmpName = $_FILES["foto"]["tmp_name"];
    $targetPath = $targetDirectory . basename($fileName);
    if (move_uploaded_file($fileTmpName, $targetPath)) {
        $uploadedFile = $targetPath;
    } else {
        echo "Si è verificato un errore durante il caricamento del file $fileName.<br>";
    }
    $marca = $_POST['marca'];
    $modello = $_POST['modello'];
    $prezzo = $_POST['prezzo'];
    $trattabile = isset($_POST['trattabile']) ? 'true' : 'false';
    $carrozzeria = $_POST['carrozzeria'];
    $anno = $_POST['anno'];
    $chilometraggio = $_POST['chilometraggio'];
    $carburante = $_POST['carburante'];
    $cambio = $_POST['cambio'];
    $potenza = $_POST['potenza'];
    $foto = $uploadedFile;
    $descrizione = $_POST['descrizione'];
    $preferito = 0;
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
    $nascosto = 0;

    $query = "INSERT INTO annuncio (marca, modello, prezzo, trattabile, carrozzeria, anno, chilometraggio, carburante, cambio, potenza, foto, descrizione, preferito, email, nascosto) 
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15)";

    $result = pg_query_params($dbconn, $query, array($marca, $modello, $prezzo, $trattabile, $carrozzeria, $anno, $chilometraggio, $carburante, $cambio, $potenza, $foto, $descrizione, $preferito, $email, $nascosto));

    if ($result) {
        echo "<script>alert('Dati inseriti correttamente nella tabella Auto');</script>";
        echo "<script>window.location.href = '../index.php';</script>";
    } else {
        echo "<script>alert('Errore durante l\'inserimento dei dati nella tabella Auto');</script>";
        echo "<script>window.location.href = '../index.php';</script>";
    }
}
?>
